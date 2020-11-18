<?php
//mysql pdo 连接
// PDO
$pdo = new PDO('mysql:host=localhost;dbname=test', 'root', '123456');
$statement = $pdo->query("SELECT * FROM account");
$row = $statement->fetch(PDO::FETCH_ASSOC);
var_dump($row);


//$statement = $pdo->exec("set @a:=1");
$statement = $pdo->query("select @a");
$row = $statement->fetch(PDO::FETCH_ASSOC);
var_dump($row);
flush();
sleep(60);

/*while($row = $statement->fetch(PDO::FETCH_ASSOC))	{
 var_dump($row);
}*/

//将多个sql执行时间跨度超过6分钟
/*$i = 0;
while($i++<10)	{
	$statement = $pdo->query("SELECT * FROM account");
	$row = $statement->fetch(PDO::FETCH_ASSOC);
	//sleep(60);
	var_dump($i);
}*/



var_dump('end');