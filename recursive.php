<?php

$arr1 = ['sign' => ['color' => [0]]];
$arr2 = ['sign' => ['color' => [1]]];

$arr = array_merge_recursive($arr1, $arr2);
$data = array_merge($arr1, $arr2);
var_dump($arr, $data);
