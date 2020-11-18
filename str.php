<?php
function is_intnumeric($val) { 
  return  is_numeric($val) && is_int($val + 0) ? true : false;
}


$a = ['3.0', '3', '1.24'];

$rs = array_map('is_intnumeric', $a);

var_export($rs);

var_dump(strval('3.0'+0));