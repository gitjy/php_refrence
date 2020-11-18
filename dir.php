<?php

//.所在目录是当前工作目录，不是当前文件所在的目录
$filename = './';
$realpath = realpath($filename);

var_dump($realpath);
include './dir/dir.php';