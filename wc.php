<?php
$all = file('./auth.fpm.log');
foreach ($all as $k => $row) {
  //preg_match('/\[.*\]/',$row, $match);
  $data = preg_split('/\t/', $row);
  $data[4] = json_decode($data[4], true);
  $row = $data[4][2];
  $row = array_filter($row);
  $row = array_merge(['msg' => $data[4][1], 'web' => $data[2], 'vtime' => $data[0]], $row);
  $rs[] = $row;
}
//var_dump($row);exit;

foreach ($rs as $row) {
   echo ($row['src'] ?? ''),$row['msg'],'--',$row['client'], '--' ,$row['appname'],$row['version'],'--', $row['c'],$row['vtime'],"<br/>";
   //echo  "'", $row['openid'],"',";
  echo $row['openid'], "<br/>";
}
