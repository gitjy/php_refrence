<?php

$filename = './';
$realpath = realpath($filename);

echo '当前文件所在目录' . __DIR__ . "\n";
echo './ 代表当前脚本所在的目录'.$realpath . "\n";
echo 'cwd 代表当前脚本所在的目录'.getcwd(). "\n";


require "./xab.php";  //错误的相对路径
//include "./dir/xab.php";  //相对路径

 echo "\nend";