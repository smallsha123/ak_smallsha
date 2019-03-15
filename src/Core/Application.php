<?php
/**
 * +----------------------------------------------------------------------
 * | swoolefy framework bases on swoole extension development, we can use it easily!
 * +----------------------------------------------------------------------
 * | Licensed ( https://opensource.org/licenses/MIT )
 * +----------------------------------------------------------------------
 * | Author: bingcool <bingcoolhuang@gmail.com || 2437667702@qq.com>
 * +----------------------------------------------------------------------
 */

namespace Smallsha\Core;



class Application
{
    /**
     * $app 应用对象
     * @var null
     */
    public static $app = null;

    /**
     * $dump 记录启动时的调试打印信息
     * @var null
     */
    public static $dump = null;

    /**
     * setApp
     * @param $object
     */
    public static function setApp( $App )
    {

        self::$app = $App;

        return true;
    }

    /**
     * getApp
     * @param  int|null $coroutine_id
     * @return $object
     */
    public static function getApp( $coroutine_id = null )
    {
        return self::$app;

    }


    /**
     * __construct
     */
    public function __construct()
    {
    }

    /**
     * __destruct
     */
    public function __destruct()
    {
    }
}