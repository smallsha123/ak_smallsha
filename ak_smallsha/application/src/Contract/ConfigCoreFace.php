<?php
namespace  Smallsha\Contract;

/**
file: ConfigCoreFace.php
+------------------------------------------------------------------------------
 * config抽象类
+------------------------------------------------------------------------------
 * @author     15239851762@163.com----smallsha
 * @version   v1.1
 */

abstract class ConfigCoreFace {
    // All of the configuration items.
    public  static $items = [];
    public  static $isLoaded = false;


    abstract static public function get($key);
    abstract public function set($key, $value);

    /**
     * 加载所有配置文件
     */
    protected  function loadConfigFiles() {
        if(!self::$isLoaded) {
            $pattern = __DIR__ . "/../config/config_*.php";
            $files = glob($pattern);
            foreach ($files as $file) {
                $prefix = basename($file, ".php");
                self::$items[$prefix] = require_once($file);
            }
            self::$isLoaded = true;
        }
    }





}