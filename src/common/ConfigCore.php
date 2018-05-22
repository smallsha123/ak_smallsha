<?php

namespace Smallsha\Common;

use Smallsha\Contract\ConfigCoreFace;
use Smallsha\Common\SmallshaException;
/**
file: ConfigCore.php
+------------------------------------------------------------------------------
 * config读取配置类
+------------------------------------------------------------------------------
 * @author    15239851762@163.com----smallsha
 * @version   v1.1
 */

final class ConfigCore extends ConfigCoreFace
{
    public static $config_storage;
    /**
     * 获取配置
     * @param $key 如"database.dbname"
     * @param null $default
     * @return mixed
     */
    public static function get( $keys )
    {
        if (!self::$config_storage instanceof self) {
            self::$config_storage = new self();
        }

        self::$config_storage->loadConfigFiles();

        if (preg_match("/(.*?)(_|\.|\/)(.*?)/iU", $keys, $matches)) {
            $prefix = $matches[1];
            $key = $matches[3];
            if(!isset( self::$items[$prefix] )){
                throw new SmallshaException("配置文件不存在，请查看参数");
            }
        } else {
            throw new SmallshaException('提供参数不正确,$Key请使用配置文件名加.|_|/来提取');
        }
        if (array_key_exists($key, self::$items[$prefix])) {
            return self::$items[$prefix][$key];
        } else {
            throw new SmallshaException('没有获取到当前配置'.$prefix.'提供的配置key,请检查cfg');
        }


    }

    /**
     * 设置配置  暂时不需要
     * @param $key
     * @param $value
     */
    public function set( $key, $value )
    {
        $params = array_filter(explode('.', $key));
        $prefix = $params[0];
        $key = $params[1];
        $this->items[$prefix][$key] = $value;
    }


}
