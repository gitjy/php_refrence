<?php
/*
$soap = new SoapClient(null, array('location'=>'http://localhost/server.php','uri' =>'http://soap/'));     
  
echo $soap->show();   
//得到：'the data you request!'   
  
//echo $soap->getUserInfo('sss');  */

$soap = new SoapClient(null, array(
      'location' => "http://localhost/soap_server.php",
      'uri'      => "http://localhost/soap_server.php",
      'trace'    => 1 ));
// echo $soap->GetInfo()."<br>";//调用方法一
echo $soap->__soapcall("GetInfo",array());//调用方法二
echo $return = $soap->__soapCall("helloWorld",array("world"));
var_dump ( $soap->__getFunctions());//获取服务器上提供的方法
