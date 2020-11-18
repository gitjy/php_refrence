<?php

/**
 * 基于swoole_clientd的weksocket 异步
 */
class websocket_client
{

    private $client;
    private $host;
    private $port;
    private $path = '/';
    public $buffer;

    protected $async = true;
    protected $handshake = false;
    protected $key;    //header key

    private $openCb;
    private $messageCb;
    private $closeCb;

    protected $maxFrameSize = 2000000;

    protected $frame = null;
    protected $syncbuffer = '';

    const HANDSHAKING = 0;
    const HANDSHAKED = 1;

    const TOKEN_LENGHT = 16;
    const GUID = '258EAFA5-E914-47DA-95CA-C5AB0DC85B11';
    const UserAgent = 'SwooleWebsocketClient';


    const WEBSOCKET_OPCODE_CONTINUATION_FRAME = 0x0;
    const WEBSOCKET_OPCODE_TEXT_FRAME = 0x1;
    const WEBSOCKET_OPCODE_BINARY_FRAME = 0x2;
    const WEBSOCKET_OPCODE_CONNECTION_CLOSE = 0x8;
    const WEBSOCKET_OPCODE_PING = 0x9;
    const WEBSOCKET_OPCODE_PONG = 0xa;

    //实例化swoole_client
    public function __construct($async = true)
    {
        $this->async = $async;
        if ($this->async) {
            $this->client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);

            //自定义异步回调处理
            $this->client->on("connect", [$this, "onConnect"]);
            $this->client->on("receive", [$this, "onReceive"]);
            $this->client->on("close", [$this, "onClose"]);
            $this->client->on("error", [$this, "onError"]);
        } else {
            $this->client = new swoole_client(SWOOLE_SOCK_TCP);    //阻塞模式
        }


        $this->buffer = "";
    }

    /**
     * Connect client to server
     * @param float $timeout
     * @return bool
     */
    public function connect($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
        //建立连接
        if (!$this->client->connect($this->host, $this->port, 1)) {
            return false;
        }
        $this->connected = true;
        //WebSocket握手 异步不能在这里握手
        if (!$this->async) {
            $this->sendHandShake();
            return $this->recv();
        }
        return;
    }

    //自定义异步事件
    //异步接收数据事件
    public function onReceive($cli, $data)
    {
        if ($this->handshake == static::HANDSHAKING) {
            $this->buffer .= $data;
            $pos = strpos($this->buffer, "\r\n\r\n", true);
            if ($pos != false) {
                $header = substr($this->buffer, 0, $pos + 4);
                $this->buffer = substr($this->buffer, $pos + 4);    //握手是buffer为空字符串
                if (true == $this->verifyUpgrade($header)) {
                    $this->handshake = static::HANDSHAKED;
                    if (isset($this->openCb))
                        call_user_func($this->openCb, $this);
                } else {
                    echo "handshake failed\n";
                }
            }
        } else if ($this->handshake == static::HANDSHAKED) {
            $this->buffer .= $data;
            try {
                //处理数据
                //$frame = $this->buffer;
                $frame = $this->processDataFrame($this->buffer);
            } catch (\Exception $e) {
                $cli->close();
                return;
            }
            if ($frame != null) {
                if (isset($this->messageCb))
                    call_user_func($this->messageCb, $this, $frame);
            }
        }
    }

    //在这里握手
    public function onConnect($cli)
    {
        $this->sendHandShake();
    }

    public function onClose($cli)
    {
        if (isset($this->closeCb))
            call_user_func($this->closeCb, $this);
    }

    public function onError($cli)
    {
        echo "error occurred\n";
    }

    //自定义WebSocket事件
    public function on($event, $callback)
    {
        if (strcasecmp($event, "open") === 0) {
            $this->openCb = $callback;
        } else if (strcasecmp($event, "message") === 0) {
            $this->messageCb = $callback;
        } else if (strcasecmp($event, "close") === 0) {
            $this->closeCb = $callback;
        } else {
            echo "$event is not supported\n";
        }
    }

    //握手
    public function sendHandShake()
    {
        $this->state = static::HANDSHAKING;
        if ($this->client->send($this->createHeader()) === false) {
            trigger_error(" send handshake failed\n");
        }

    }


    //生成握手token
    private
    static function generateToken($length)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!"§$%&/()=[]{}';
        $useChars = array();
        // select some random chars:
        for ($i = 0; $i < $length; $i++) {
            $useChars[] = $characters[mt_rand(0, strlen($characters) - 1)];
        }
        // Add numbers
        array_push($useChars, rand(0, 9), rand(0, 9), rand(0, 9));
        shuffle($useChars);
        $randomString = trim(implode('', $useChars));
        $randomString = substr($randomString, 0, self::TOKEN_LENGHT);
        return base64_encode($randomString);
    }

    //验证握手token
    public function verifyUpgrade($packet)
    {
        $headers = explode("\r\n", $packet);
        unset($headers[0]);
        $header = [];
        foreach ($headers as $headerRow) {
            $arr = explode(":", $headerRow);
            if (count($arr) == 2) {
                list($field, $value) = $arr;
                $header[trim($field)] = trim($value);
            }
        }

        if ($header['Sec-WebSocket-Accept'] != base64_encode(pack('H*', sha1($this->key . self::GUID)))) {
            $this->disconnect();
            return false;
        }
        $this->handshake = self::HANDSHAKED;
        $this->header = $header;
        return true;
    }

    //生成握手header头
    final protected function createHeader()
    {
        $host = $this->host;
        if ($host === '127.0.0.1' || $host === '0.0.0.0') {
            $host = 'localhost';
        }

        $this->key = static::generateToken(self::TOKEN_LENGHT);

        return "GET {$this->path} HTTP/1.1" . "\r\n" .
            "Origin: null" . "\r\n" .
            "Host: {$host}:{$this->port}" . "\r\n" .
            "Sec-WebSocket-Key: {$this->key}" . "\r\n" .
            "User-Agent: " . self::UserAgent . "\r\n" .
            "Upgrade: Websocket" . "\r\n" .
            "Connection: Upgrade" . "\r\n" .
            "Sec-WebSocket-Protocol: wamp" . "\r\n" .
            "Sec-WebSocket-Version: 13" . "\r\n" . "\r\n";
    }

    //发送数据
    public function send($data, $type = 'text')
    {
        switch ($type) {
            case 'text':
                $_type = WEBSOCKET_OPCODE_TEXT;
                break;
            case 'binary':
            case 'bin':
                $_type = WEBSOCKET_OPCODE_BINARY;
                break;
            case 'ping':
                $_type = self::WEBSOCKET_OPCODE_PING;
                break;
            case 'close':
                $_type = self::WEBSOCKET_OPCODE_CONNECTION_CLOSE;
                break;
            case 'ping':
                $_type = self::WEBSOCKET_OPCODE_PING;
                break;
            case 'pong':
                $_type = self::WEBSOCKET_OPCODE_PONG;
                break;
            default:
                echo "$type is not supported\n";
                return;
        }
        $data = swoole_websocket_server::pack($data, $_type);    //封装为websocket数据发送
        var_dump($data);
        $this->client->send($data);
    }

    //同步接收收据
    public function recv()
    {
        if (self::HANDSHAKING == $this->handshake) {
            $headerBuffer = '';
            while (true) {
                $_tmp = $this->client->recv();
                if ($_tmp) {
                    $headerBuffer .= $_tmp;
                    $offset = stripos($headerBuffer, "\r\n\r\n");
                    if ($offset === false) {
                        continue;
                    }
                    $header = substr($headerBuffer, 0, $offset + 4);
                    $this->buffer = substr($headerBuffer, $offset + 4);
                } else {
                    return false;
                }
                return $this->verifyUpgrade($header);
            }
        }
        if (self::HANDSHAKED != $this->handshake) {
            trigger_error("not complete handshake.");
            return false;
        }

        if ($this->buffer and $offset = stripos($this->buffer, "\r\n\r\n") !== false) {
            $packet = substr($this->buffer, 0, $offset + 4);
            $next = substr($this->buffer, $offset + 4);
            if ($next) {
                $this->buffer = $next;
            }
            $frame = $this->processDataFrame($this->buffer);
            if ($frame) {
                return $frame->data;
            }
        } else {
            //阻塞模式要不断去取数据
            while (true) {
                $data = $this->client->recv();    //没有数据再次取，会报错,所以每次接收收据，判断是否是完整的frame,是的话返回
                echo "recv...\n";
                if ($this->buffer) {
                    $data = $this->buffer . $data;
                    $this->buffer = '';
                }
                if (!$data) {
                    return false;
                }
                //$frame = $this->pop($data);     //每一次都处理buffer
                //$frame = $this->processDataFrame($data);
                $frame = swoole_websocket_server::unpack($data);
                if ($frame) {
                    return $frame->data;
                }
            }
        }
    }

    //返回socketclient
    public function getTcpClient()
    {
        return $this->client;
    }

    //解析接收的frame,完整的数据
    public function processDataFrame(&$packet)
    {
        if (strlen($packet) < 2)
            return null;
        $header = substr($packet, 0, 2);
        $index = 0;
        //fin:1 rsv1:1 rsv2:1 rsv3:1 opcode:4
        $handle = ord($packet[$index]);
        $finish = ($handle >> 7) & 0x1;
        $rsv1 = ($handle >> 6) & 0x1;
        $rsv2 = ($handle >> 5) & 0x1;
        $rsv3 = ($handle >> 4) & 0x1;
        $opcode = $handle & 0xf;
        $index++;
        //mask:1 length:7
        $handle = ord($packet[$index]);
        $mask = ($handle >> 7) & 0x1;
        //0-125
        $length = $handle & 0x7f;
        $index++;
        //126 short
        if ($length == 0x7e) {
            if (strlen($packet) < $index + 2)
                return null;
            //2 byte
            $handle = unpack('nl', substr($packet, $index, 2));
            $index += 2;
            $length = $handle['l'];
        } //127 int64
        elseif ($length > 0x7e) {
            if (strlen($packet) < $index + 8)
                return null;
            //8 byte
            $handle = unpack('Nh/Nl', substr($packet, $index, 8));
            $index += 8;
            $length = $handle['l'];
            if ($length > static::maxPacketSize) {
                throw new \Exception("frame length is too big.\n");
            }
        }
        //mask-key: int32
        if ($mask) {
            if (strlen($packet) < $index + 4)
                return null;
            $mask = array_map('ord', str_split(substr($packet, $index, 4)));
            $index += 4;
        }
        if (strlen($packet) < $index + $length)
            return null;
        $data = substr($packet, $index, $length);
        $index += $length;
        $packet = substr($packet, $index);
        $frame = new WebSocketFrame;
        $frame->finish = $finish;
        $frame->opcode = $opcode;
        $frame->data = $data;
        return $frame;
    }

    //-----------------

    /**
     * 弹出frame 阻塞模式
     * @return bool|WebSocketFrame
     * @throws Swoole\Http\WebSocketException
     */
    function pop($data)
    {
        $this->syncbuffer .= $data;
        //当前有等待的frame
        if ($this->frame) {

            if (strlen($this->syncbuffer) >= $this->frame->length) {
                //分包
                $this->frame->data = substr($this->syncbuffer, 0, $this->frame->length);
                self::unMask($this->frame);
                $frame = $this->frame;
                //进入新的frame解析流程
                $this->frame = null;
                $this->syncbuffer = substr($this->syncbuffer, $frame->length);
                return $frame;
            } else {
                return false;
            }
        }
        $buffer = &$this->syncbuffer;
        if (strlen($buffer) < 2) {
            return false;
        }
        $frame = new WebSocketFrame;
        $data_offset = 0;
        //fin:1 rsv1:1 rsv2:1 rsv3:1 opcode:4
        $handle = ord($buffer[$data_offset]);
        $frame->finish = ($handle >> 7) & 0x1;
        $frame->rsv1 = ($handle >> 6) & 0x1;
        $frame->rsv2 = ($handle >> 5) & 0x1;
        $frame->rsv3 = ($handle >> 4) & 0x1;
        $frame->opcode = $handle & 0xf;
        $data_offset++;
        //mask:1 length:7
        $handle = ord($buffer[$data_offset]);
        $frame->mask = ($handle >> 7) & 0x1;
        //0-125
        $frame->length = $handle & 0x7f;
        $length =  &$frame->length;
        $data_offset++;
        //126 short
        if ($length == 0x7e) {
            //2 byte
            $handle = unpack('nl', substr($buffer, $data_offset, 2));
            $data_offset += 2;
            $length = $handle['l'];
        } //127 int64
        elseif ($length > 0x7e) {
            //8 byte
            $handle = unpack('Nh/Nl', substr($buffer, $data_offset, 8));
            $data_offset += 8;
            $length = $handle['l'];
            //超过最大允许的长度了，恶意的连接，需要关闭
            if ($length > $this->maxFrameSize) {
                throw new Exception("frame length is too big.", self::ERR_TOO_LONG);
            }
        }
        //mask-key: int32
        if ($frame->mask) {
            $frame->mask = array_map('ord', str_split(substr($buffer, $data_offset, 4)));
            $data_offset += 4;
        }
        //把头去掉
        $buffer = substr($buffer, $data_offset);
        //数据长度为0的帧
        if (0 === $length) {
            $frame->finish = true;
            $frame->data = '';
            return $frame;
        }
        //完整的一个数据帧
        if (strlen($buffer) >= $length) {
            $frame->finish = true;
            $frame->data = substr($buffer, 0, $length);
            //清理buffer
            $buffer = substr($buffer, $length);
            self::unMask($frame);
            return $frame;
        } //需要继续等待数据
        else {
            $frame->finish = false;
            $this->frame = $frame;
            return false;
        }
    }

    /**
     * 这里传递的是对象，即对象数据修改
     * @param $frame WebSocketFrame
     */
    static function unMask($frame)
    {
        if ($frame->mask) {
            $maskC = 0;
            $data = $frame->data;
            for ($j = 0, $_length = $frame->length; $j < $_length; ++$j) {
                $data[$j] = chr(ord($frame->data[$j]) ^ $frame->mask[$maskC]);
                $maskC = ($maskC + 1) % 4;
            }
            $frame->data = $data;
        }
    }

    //-------


    /**
     * Disconnect from server
     */
    public function disconnect()
    {
        $this->connected = false;
        $this->client->close();
        echo "disconnect\n";
    }
}

class WebSocketFrame
{
    public $finish;
    public $opcode;
    public $data;

    public $length;
    public $rsv1;
    public $rsv2;
    public $rsv3;
    public $mask;
}


//--------
class Console
{
    public static function stdin($raw = false)
    {
        return $raw ? fgets(\STDIN) : rtrim(fgets(\STDIN), PHP_EOL);
    }

    //输出
    public static function stdout($string)
    {
        return fwrite(\STDOUT, $string);
    }

    public static function stderr($string)
    {
        return fwrite(\STDERR, $string);
    }
}