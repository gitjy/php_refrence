<?php
include "common/json.php";
$filename = 'data.json';
$str = file_get_contents($filename);
$data = json_decode($str);
view::display($data);
