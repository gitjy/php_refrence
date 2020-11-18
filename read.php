<?php
//header('Content-Type:text/html;charset=utf-8');
$filename = __DIR__ . '/file.csv';
$handle = @fopen($filename, "r");
//$keys = format(fgets($handle));
//$keys = fgetcsv($handle);
$i = 0;
if ($handle) {
    while (($buffer = fgetcsv($handle)) !== false) {
        // $vals = $buffer;
        // $row = array_combine($keys, $vals);
        $row = $buffer;
        $i++;
        echo "<pre>";
        print_r ($buffer);
        echo "</pre>";
        if (10 == $i) {
          exit('hello');
        }
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
}

echo "$i user";

function format($str) {
  $buffer = trim($str);
  $keys = explode(',', $buffer);
  $keys = array_map(function($v) { return trim($v, '"');}, $keys);
  return $keys;
}

//方案二 超出内存

$all = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
//$all = file_get_contents($filename);
$wc = [];
var_dump($all);
while ($list = array_splice($all, 0,1000)) {
    foreach ($list as $row) {
        var_dump($row);exit;
    }
}
