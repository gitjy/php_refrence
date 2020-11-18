<?php
//key不应该改变
$data = [
  ['issue' =>1],['issue' =>1],['issue' =>1],['issue' =>1],['issue' =>1],];
array_walk($data, function(&$item, &$key) {$key = 'id_' . $key;$item['issue'] = 0;var_dump($key);});
print_r($data);


$records = array(
array(
 'id' => 2135,
 'first_name' => 'John',
 'last_name' => 'Doe',
),
array(
 'id' => 3245,
 'first_name' => 'Sally',
 'last_name' => 'Smith',
),
array(
 'id' => 5342,
 'first_name' => 'Jane',
 'last_name' => 'Jones',
),
array(
 'id' => 5623,
 'first_name' => 'Peter',
 'last_name' => 'Doe',
)
);
$records  = array_column($records, 'last_name', 'id');  
print_r($records);