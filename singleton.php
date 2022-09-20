<?php
/**
* 单例模式
**/
class Singleton 
{
	protected static $instance;

	private function __construct(){}

	public static function getInstance(...$args)
	{
		if (!isset(self::$instance)) {
			self::$instance = new static(...$args);
		}
		return self::$instance;
	}

	/**
     * 设置当前容器的实例
     * @access public
     * @param object|Closure $instance
     * @return void
     */
    public static function setInstance($instance): void
    {
        static::$instance = $instance;
    }
}

/**
 * App 基础类
 */
class App extends Singleton
{

	//在单例类的继承类，再次声明实例静态变量，会导致不同的静态变量
	protected static $instance; 
    /**
     * 架构方法
     * @access public
     * @param string $rootPath 应用根目录
     */
    public function __construct(string $rootPath = '')
    {
        static::setInstance($this);
    }

}

//Fatal error: Uncaught Error: Call to private Singleton::__construct()
//$instance = new Singleton(); 


//$instance = Singleton::getInstance();

$app = new App();

var_dump(Singleton::getInstance());


