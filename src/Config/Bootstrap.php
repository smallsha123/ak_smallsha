<?php
require_once __DIR__ . '/const.php';  //加载常量

class Bootstrap
{

    private static $config;

    private static $params;

    public static function getInstance( array $config, array $params )
    {
        static::$config = require_once __DIR__ . '/config.php';
        if (!empty($config)) {
            static::$config = array_merge(static::$params, $config);  //加载配置文件
        }
        static::$params = require_once __DIR__ . '/params.php';
        if (!empty($params)) {
            static::$params = array_merge(static::$params, $params);  //加载常用参数11
        }

        return new \Smallsha\Core\App([static::$config, static::$params]);
    }
}



