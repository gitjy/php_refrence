<?php
/**
* 单例模式
**/
class Singleton 
{
	private static $instance;

	private function __construct(){}

	public static function getInstance(...$args)
	{
		if (!isset(self::$instance)) {
			self::$instance = new static(...$args);
		}
		return self::$instance;
	}
}

//Fatal error: Uncaught Error: Call to private Singleton::__construct()
//$instance = new Singleton(); 


$instance = Singleton::getInstance();