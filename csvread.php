<?php
/**
* 读取一个CSV文件
**/
//header('Content-Type:text/html;charset=gbk');
$filename = __DIR__ . '/file.csv';
$filename =  './file.csv';
$infile = __DIR__ . '/data.json';
$handle = @fopen($filename, "r");
//$keys = format(fgets($handle));
//$keys = fgetcsv($handle);
if ($handle) {
    while (($buffer = fgetcsv($handle)) !== false) {
        $vals = $buffer;
        //$row = array_combine($keys, $vals);
        echo "<pre>";
        print_r ($buffer);
        echo "</pre>";
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
}

function format($str) {
  $buffer = trim($str);
  $keys = explode(',', $buffer);
  $keys = array_map(function($v) { return trim($v, '"');}, $keys);
  return $keys;
}


//方案二 超出内存
/*
$all = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
*/