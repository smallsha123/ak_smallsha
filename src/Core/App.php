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
use Smallsha\Core\Env;
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

    /*
     * 插件
     * */
    public $plugins = null;

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
            $this->plugins = \Smallsha\Classes\PluginManager::getInstance(); //加载插件
            parent::creatObject();   //加载组件
            static::bootstrap(); //初始化钩子函数
            Application::setApp($this);  //添加到注册树上
        }catch (\Smallsha\Core\SmallshaException $e){
            exit($e->getMessage());
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
        //TODO 钩子加载
        $envObj = new Env();
        $envObj->load(SMROOT . 'Config/.env');
    }


}