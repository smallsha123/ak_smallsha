<?php
namespace  Smallsha\Classes;

use Smallsha\Common\ConfigCore;
require_once dirname(__FILE__).'/../../../../autoload.php';
class Test {
    public $_cfg;

    public function __construct()
    {
        $this->aa();
    }

    public function aa(){
        try{

          $a =   Config('config_redis/ip');
        }catch (\Exception $e){
            echo $e->getMessage();
        }

    }

}
new Test();
