<?php

namespace Smallsha\Classes;


class Plugin
{


    // 注册添加插件
    public static function add( $name, $func )
    {
        $GLOBALS['hookList'][$name][] = $func;
    }

    // 执行插件
    public static function run( $name, $params = null )
    {
        foreach ($GLOBALS['hookList'][$name] as $k => $v) {
            call_user_func($v, $params);
        }
    }



}