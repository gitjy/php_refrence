<?php
$tcpPort = 9503;
$webPort = 9502;
$sock = fsockopen('127.0.0.1', $webPort, $errno, $errstr,1);

if  ( !$sock ) {
    echo $errstr . "<br/>\n";
} else {
    socket_set_blocking($sock, false);
    $out = "";
    $out .= "GET / HTTP/1.1\r\n";
    $out .= "Upgrade: websocket\r\n";
    $out .= "Connection: Upgrade\r\n";
    $out .= "Host: example.com\r\n";
    $out .= "Origin: http://example.com\r\n";
    $out .= "Sec-WebSocket-Key: sN9cRrP/n9NdMgdcy2VJFQ==Sec\r\n";
    $out .= "Sec-WebSocket-Version: 13\r\n\r\n";
    fwrite($sock, $out);
    fwrite($sock, "send data…\r\n");
    fwrite($sock, "end\r\n");
    while(!feof($sock)) {
        echo fread($sock, 128);
   }
    fclose($sock);
}