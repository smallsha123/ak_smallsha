<?php
/**
+----------------------------------------------------------------------
| swoolefy framework bases on swoole extension development, we can use it easily!
+----------------------------------------------------------------------
| Licensed ( https://opensource.org/licenses/MIT )
+----------------------------------------------------------------------
| Author: bingcool <bingcoolhuang@gmail.com || 2437667702@qq.com>
+----------------------------------------------------------------------
*/

namespace Smallsha\Core;

use Smallsha\Core\Small;

use Smallsha\Core\Application;

class App extends \Smallsha\Core\Component {

	/**
	 * $config 当前应用层的配置 
	 * @var null
	 */
	public $config = null;

    /**
     * $config 当前应用层的配置
     * @var null
     */
    public $params = null;

	/**
	 * __construct
	 * @param $config 应用层配置
	 */
	public function __construct(array $sys_config=[]) {

	    list($config,$params) = $sys_config;
		// 将应用层配置保存在上下文的服务
		$this->config = Small::$appConfig = $config;
		$this->params = Small::$params = $params;


		try{
            parent::creatObject();   //加载组件
            Application::setApp($this);  //注册组件

            static::bootstrap(); //初始化钩子函数

            // Event 将事件抽象出来


        }catch (\Smallsha\Core\SmallshaException $e){
		    throw $e;
        }
	}


    public static function bootstrap(){

	    if(Small::$params['development'] == TRUE){
	        ini_set('display_errors',1);
	        error_reporting(E_ALL);
        }else{
            ini_set('display_errors',0);
            error_reporting(0);
        }
        ini_set('date.timezone','Asia/Shanghai'); //设置时区
        new \Smallsha\Classes\PluginManager(); //加载插件
        return true;
    }


}