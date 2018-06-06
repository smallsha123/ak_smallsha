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

/**
 * 冒泡排序
 *
 * @author   smallsha <2233850112@qq.com>
 * @date     2017/6/16
 * @license  MIT
 * -------------------------------------------------------------
 * 思路分析：就是像冒泡一样，每次从数组当中 冒一个最大的数出来。 
 * -------------------------------------------------------------
 * 你可以这样理解：（从小到大排序）存在10个不同大小的气泡，
 * 由底至上的把较少的气泡逐步地向上升，这样经过遍历一次后最小的气泡就会被上升到顶（下标为0）
 * 然后再从底至上地这样升，循环直至十个气泡大小有序。
 * 在冒泡排序中，最重要的思想是两两比较，将两者较少的升上去
 *
 */
// +--------------------------------------------------------------------------
// | 解题方式    | 这儿，可能有用的解决方案
// +--------------------------------------------------------------------------
/**
 * BubbleSort
 *
 * @param array $container
 * @return array
 */
 if(! function_exists ('BubbleSort')) {
	 function BubbleSort(array $container)
		{
			$count = count($container);
			for ($j = 1; $j < $count; $j++) {
				for ($i = 0; $i < $count - $j; $i++) {
					if ($container[$i] > $container[$i + 1]) {
						$temp = $container[$i];
						$container[$i] = $container[$i + 1];
						$container[$i + 1] = $temp;
					}
				}
			}
			return $container;
		}
}



/**
 * 插入排序
 * @author   smallsha <2233850112@qq.com>
 * @date     2017/6/17
 * @license  MIT
 * -------------------------------------------------------------
 * 思路分析：每步将一个待排序的纪录，按其关键码值的大小插入前面已经排序的文件中适当位置上，直到全部插入完为止。
 * -------------------------------------------------------------
 *
 * 算法适用于少量数据的排序，时间复杂度为O(n^2)。是稳定的排序方法。
 * 插入算法把要排序的数组分成两部分：第一部分包含了这个数组的所有元素，
 * 但将最后一个元素除外（让数组多一个空间才有插入的位置），而第二部分就只包含这一个元素（即待插入元素）。
 * 在第一部分排序完成后，再将这个最后元素插入到已排好序的第一部分中。
 *
 */
// +--------------------------------------------------------------------------
// | 解题方式    | 这儿，可能有用的解决方案
// +--------------------------------------------------------------------------
/**
 * InsertSort
 *
 * @param array $container
 * @return array
 */
if(! function_exists ('InsertSort')) {
		function InsertSort(array $container)
		{
			$count = count($container);
			for ($i = 1; $i < $count; $i++){
				$temp = $container[$i];
				$j    = $i - 1;
				// Init
				while($j >= 0 && $container[$j] > $temp){
					$container[$j+1] = $container[$j];
					$j--;
				}
				if($i != $j+1) 
					$container[$j+1] = $temp;
			}
			return $container;
		}
}

/**
 * 快速排序
 *
 * @author  smallsha <2233850112@qq.com>
 * @date     2017/6/17
 * @license  MIT
 * -------------------------------------------------------------
 * 思路分析：从数列中挑出一个元素，称为 “基准”（pivot) 
 * 大O表示： O(n log n) 最糟 O(n 2)
 * -------------------------------------------------------------
 * 重新排序数列，所有元素比基准值小的摆放在基准前面，C 语言中的 qsort就是快速排序
 * 所有元素比基准值大的摆在基准的后面（相同的数可以到任一边）。
 * 递归地（recursive）把小于基准值元素的子数列和大于基准值元素的子数列排序
 */
// +--------------------------------------------------------------------------
// | 解题方式
// +--------------------------------------------------------------------------
/**
 * QuickSort
 *
 * @param array $container
 * @return array
 */
 if(! function_exists (QuickSort)) {
		function QuickSort(array $container)
		{
			$count = count($container);
			if ($count <= 1) { // 基线条件为空或者只包含一个元素，只需要原样返回数组
				return $container;
			}
			$pivot = $container[0]; // 基准值 pivot
			$left  = $right = [];
			for ($i = 1; $i < $count; $i++) {
				if ($container[$i] < $pivot) {
					$left[] = $container[$i];
				} else {
					$right[] = $container[$i];
				}
			}
			$left  = QuickSort($left);
			$right = QuickSort($right);
			return array_merge($left, [$container[0]], $right);
		}
 }

 