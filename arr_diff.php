<?php
$a = ['a' =>1, 'b' =>3];
$b = ['a' =>2,'c' =>3];

$diff = array_diff($a, $b);
var_dump($diff);