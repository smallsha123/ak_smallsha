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

use Throwable;

class SmallshaException extends \Exception
{
    public function __construct( $message = "", $code = 0, Throwable $previous = null )
    {
        parent::__construct($message, $code, $previous);
    }

    public function error($msg='',$code=2){
        $arr = [
            'code' => $code,
            'msg'=>$msg != '' ? $msg : '文件:'.$this->getFile().'||第'.$this->getLine()."行||报错信息".$this->getMessage(),
        ];
        exit(json_encode($arr));
    }
}


