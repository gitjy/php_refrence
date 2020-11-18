<?php
//对象的数组访问方式
$obj = new ArrayObject([], ArrayObject::ARRAY_AS_PROPS);
//$obj = new ArrayObject([], ArrayObject::STD_PROP_LIST);
//$obj = new ArrayObject([]);
$obj->name = 'liming';
$obj['gender'] = 'man';
//var_dump($obj['name']);


var_dump($obj['gender']);

var_dump($obj,$obj->getArrayCopy());
