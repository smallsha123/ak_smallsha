<?php
/**
 * 是否是AJAx提交的
 * @return bool
 */
if(!function_exists('isAjax')){
	function isAjax(){
	  if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
	    return true;
	  }else{
	    return false;
	  }
	}
}

/**
 * 是否是GET提交的
 */
if(!function_exists('isGet')){
	function isGet(){
	  return $_SERVER['REQUEST_METHOD'] == 'GET' ? true : false;
	}
}

/**
 * 是否是POST提交
 * @return int
 */
if(!function_exists('isPost')){
	function isPost() {
	  return ($_SERVER['REQUEST_METHOD'] == 'POST' && checkurlHash($GLOBALS['verify']) && (empty($_SERVER['HTTP_REFERER']) || preg_replace("~https?:\/\/([^\:\/]+).*~i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("~([^\:]+).*~", "\\1", $_SERVER['HTTP_HOST']))) ? true : false;
	}
}


/**
 * 对象数组转换
 * @return int
 */
if(!function_exists('jsonArray')){
	function jsonArray($to){
		if(gettype($to) == 'array'){
		  return $a= json_decode( json_encode( $to));
		}elseif(gettype($to)== 'object'){
          return json_decode( json_encode( $to),true);
		}
	}
}

/**
 * base64  结合 serialize 转化 array 
 * @return int
 */
if(!function_exists('baseSerialize')){
	function baseSerialize($to){
		if(gettype($to) == 'array'){
		  return base64_encode( serialize($to));
		}else{
          return unserialize( base64_decode( $to));
		}
	}
}

	/**
     * PHP精确计算  主要用于货币的计算用
     * @param $n1 第一个数
     * @param $symbol 计算符号 + - * / %
     * @param $n2 第二个数
     * @param string $scale  精度 默认为小数点后两位
     * @return  string
     */
    function ncPriceCalculate($n1,$symbol,$n2,$scale = '2'){
        $res = "";
        switch ($symbol){
            case "+"://加法
                $res = bcadd($n1,$n2,$scale);break;
            case "-"://减法
                $res = bcsub($n1,$n2,$scale);break;
            case "*"://乘法
                $res = bcmul($n1,$n2,$scale);break;
            case "/"://除法
                $res = bcdiv($n1,$n2,$scale);break;
            case "%"://求余、取模
                $res = bcmod($n1,$n2,$scale);break;
            default:
                $res = "";break;
        }
        return $res;
    }
    /**
     * 价格由元转分
     * @param $price 金额
     * @return int
     */
    function ncPriceYuan2fen($price){
        $price = (int) ncPriceCalculate(100,"*", ncPriceFormat($price));
        return $price;
    }
    /**
    * 价格格式化
    *
    * @param int	$price
    * @return string	$price_format
    */
    function ncPriceFormat($price) {
    	$price_format	= number_format($price,2,'.','');
    	return $price_format;
    }

	
	function generate_rand_string( $length = 8 ) { 
		// 密码字符集，可任意添加你需要的字符 
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; 
		$password = ''; 
		for ( $i = 0; $i < $length; $i++ ) 
		{ 
		// 这里提供两种字符获取方式 
		// 第一种是使用 substr 截取$chars中的任意一位字符； 
		// 第二种是取字符数组 $chars 的任意元素 
		// $password .= substr($chars, mt_rand(0, strlen($chars) – 1), 1); 
		$password .= $chars[ mt_rand(0, strlen($chars) - 1) ]; 
		} 
		return $password; 
	} 
	
if (!function_exists('AkConfig')) {
    function AkConfig( $keys )
    {
        return ConfigCore::get($keys);
    }
}


 