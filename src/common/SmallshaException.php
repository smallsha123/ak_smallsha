<?php
namespace Smallsha\Common;

/**
file: SmallshaException.php
+------------------------------------------------------------------------------
 * 异常捕获类
+------------------------------------------------------------------------------
 * @author     15239851762@163.com----smallsha
 * @version   v1.1
 */

class SmallshaException extends \Exception {

   public function errorMessage()
    {
        return $this->getMessage();
    }
}
	
