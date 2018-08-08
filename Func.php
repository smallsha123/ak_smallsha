<?php

//使用ak_smallsha默认自定义记载func
function choose_bind_func( $filename = '', $is_all = false )
{
    $rest_dir = __DIR__ . '/src/Function';
    if ($is_all) {
        getAkFunc(); // 自动加载到Function中全部方法
    } else {
        $file_dir = $rest_dir . '/' . $filename . '.php';
        if (is_file($file_dir)) {
            require_once $file_dir;
        } else {
            trigger_error($file_dir . '文件不存在', E_USER_WARNING); //E_USER_ERROR E_USER_WARNING  E_USER_NOTICE //默认
        }
    }


}


function getAkFunc()
{
    $handle = opendir(__DIR__ . '/src/Function');
    if (!empty($handle)) {
        while ($dir = readdir($handle)) {
            if ($dir != '.' && $dir != '..') {
                $files[] = $dir;
            }
        }
    }
    foreach ($files as $v) {
        $file_dir = __DIR__ . '/src/Function/' . $v;
        if (is_file($file_dir)) {
            require_once $file_dir;
        }
    }

}

/**
 * 是否是AJAx提交的
 * @return bool
 */
if (!function_exists('isAjax')) {
    function isAjax()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * 是否是GET提交的
 */
if (!function_exists('isGet')) {
    function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] == 'GET' ? true : false;
    }
}

/**
 * 是否是POST提交
 * @return int
 */
if (!function_exists('isPost')) {
    function isPost()
    {
        return ($_SERVER['REQUEST_METHOD'] == 'POST' && checkurlHash($GLOBALS['verify']) && (empty($_SERVER['HTTP_REFERER']) || preg_replace("~https?:\/\/([^\:\/]+).*~i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("~([^\:]+).*~", "\\1", $_SERVER['HTTP_HOST']))) ? true : false;
    }
}


/**
 * 对象数组转换
 * @return int
 */
if (!function_exists('jsonArray')) {
    function jsonArray( $to )
    {
        if (gettype($to) == 'array') {
            return $a = json_decode(json_encode($to));
        } elseif (gettype($to) == 'object') {
            return json_decode(json_encode($to), true);
        }
    }
}

/**
 * base64  结合 serialize 转化 array
 * @return int
 */
if (!function_exists('baseSerialize')) {
    function baseSerialize( $to )
    {
        if (gettype($to) == 'array') {
            return base64_encode(serialize($to));
        } else {
            return unserialize(base64_decode($to));
        }
    }
}

/**
 * PHP精确计算  主要用于货币的计算用
 * @param $n1 第一个数
 * @param $symbol 计算符号 + - * / %
 * @param $n2 第二个数
 * @param string $scale 精度 默认为小数点后两位
 * @return  string
 */
function ncPriceCalculate( $n1, $symbol, $n2, $scale = '2' )
{
    $res = "";
    switch ($symbol) {
        case "+"://加法
            $res = bcadd($n1, $n2, $scale);
            break;
        case "-"://减法
            $res = bcsub($n1, $n2, $scale);
            break;
        case "*"://乘法
            $res = bcmul($n1, $n2, $scale);
            break;
        case "/"://除法
            $res = bcdiv($n1, $n2, $scale);
            break;
        case "%"://求余、取模
            $res = bcmod($n1, $n2, $scale);
            break;
        default:
            $res = "";
            break;
    }
    return $res;
}

/**
 * 价格由元转分
 * @param $price 金额
 * @return int
 */
function ncPriceYuan2fen( $price )
{
    $price = (int)ncPriceCalculate(100, "*", ncPriceFormat($price));
    return $price;
}

/**
 * 价格格式化
 *
 * @param int $price
 * @return string    $price_format
 */
function ncPriceFormat( $price )
{
    $price_format = number_format($price, 2, '.', '');
    return $price_format;
}


function generate_rand_string( $length = 8 )
{
    // 密码字符集，可任意添加你需要的字符
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        // 这里提供两种字符获取方式
        // 第一种是使用 substr 截取$chars中的任意一位字符；
        // 第二种是取字符数组 $chars 的任意元素
        // $password .= substr($chars, mt_rand(0, strlen($chars) – 1), 1);
        $password .= $chars[mt_rand(0, strlen($chars) - 1)];
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
if (!function_exists('BubbleSort')) {
    function BubbleSort( array $container )
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
if (!function_exists('InsertSort')) {
    function InsertSort( array $container )
    {
        $count = count($container);
        for ($i = 1; $i < $count; $i++) {
            $temp = $container[$i];
            $j = $i - 1;
            // Init
            while ($j >= 0 && $container[$j] > $temp) {
                $container[$j + 1] = $container[$j];
                $j--;
            }
            if ($i != $j + 1)
                $container[$j + 1] = $temp;
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
if (!function_exists('QuickSort')) {
    function QuickSort( array $container )
    {
        $count = count($container);
        if ($count <= 1) { // 基线条件为空或者只包含一个元素，只需要原样返回数组
            return $container;
        }
        $pivot = $container[0]; // 基准值 pivot
        $left = $right = [];
        for ($i = 1; $i < $count; $i++) {
            if ($container[$i] < $pivot) {
                $left[] = $container[$i];
            } else {
                $right[] = $container[$i];
            }
        }
        $left = QuickSort($left);
        $right = QuickSort($right);
        return array_merge($left, [$container[0]], $right);
    }
}

if (!function_exists('xml_to_array')) {
    function xml_to_array( $data )
    {
        $data = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        return json_decode(json_encode($data), true);
    }
}

//https请求(支持GET和POST)
function sm_request( $url, $data = null )
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
//    curl_setopt($curl, CURLOPT_HEADER, TRUE);    //表示需要response header
    curl_setopt($curl, CURLOPT_NOBODY, FALSE);  //表示需要response body
//    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, FALSE);
    curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($curl, CURLOPT_TIMEOUT, 120);
    if (!empty($data)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == '200') {
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($output, 0, $headerSize);
        $body = substr($output, $headerSize);
    }
    //var_dump(curl_error($curl));

    curl_close($curl);
    return $output;
}


if (!function_exists('mkdirs')) {
    //防止直接传入文件路径创建不成功
    function mkdirs( $path )
    {

        if (!is_dir($path)) {

            mkdirs(dirname($path));

            mkdir($path);

        }
        return is_dir($path);

    }
}

if (!function_exists('file_copy')) {

    function file_copy( $src, $des, $filter )
    {

        $dir = opendir($src);

        mkdir($des);

        while (false !== ($file = readdir($dir))) {

            if (($file != '.') && ($file != '..')) {

                if (is_dir($src . '/' . $file)) {


                    mkdirs($src . '/' . $file);

                    file_copy($src . '/' . $file, $des . '/' . $file, $filter);

                } elseif (!in_array(substr($file, strrpos($file, '.') + 1), $filter)) {

                    copy($src . '/' . $file, $des . '/' . $file);

                }

            }

        }

        closedir($dir);

    }

}


if (!function_exists('rmdirs')) {
    //删除目录
    function rmdirs( $path, $clean = false )
    {

        if (!is_dir($path)) {
            return false;
        }

        $files = glob($path . '/*');

        if ($files) {
            foreach ($files as $file) {
                is_dir($file) ? rmdirs($file) : @unlink($file);
            }
        }

        return $clean ? true : @rmdir($path);

    }
}

if (!function_exists('getips')) {
    //获取客户端ip
    function getips()
    {

        static $ip = '';

        $ip = $_SERVER['REMOTE_ADDR'];

        if (isset($_SERVER['HTTP_CDN_SRC_IP'])) {

            $ip = $_SERVER['HTTP_CDN_SRC_IP'];

        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {

            $ip = $_SERVER['HTTP_CLIENT_IP'];

        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {

            foreach ($matches[0] AS $xip) {

                if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {

                    $ip = $xip;

                    break;

                }

            }

        }

        if (preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $ip)) {

            return $ip;

        } else {

            return '127.0.0.1';

        }

    }
}

if (!function_exists('get_time')) {
    //获取系统时间 优于time()
    function get_time( $is_format = false )
    {
        return $is_format ? date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']) : $_SERVER['REQUEST_TIME'];
    }
}


if (!function_exists('random')) {

    function random( $length, $numeric = FALSE )
    {

        $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);

        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));

        if ($numeric) {

            $hash = '';

        } else {

            $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);

            $length--;

        }

        $max = strlen($seed) - 1;

        for ($i = 0; $i < $length; $i++) {

            $hash .= $seed{mt_rand(0, $max)};

        }

        return $hash;

    }
}


if (!function_exists('timeFromNow')) {
    function timeFromNow( $dateline )
    {
        if (empty($dateline)) return false;
        $seconds = time() - $dateline;
        if ($seconds < 60) {
            return "1分钟前";
        } elseif ($seconds < 3600) {
            return floor($seconds / 60) . "分钟前";
        } elseif ($seconds < 24 * 3600) {
            return floor($seconds / 3600) . "小时前";
        } elseif ($seconds < 48 * 3600) {
            return date("昨天 H:i", $dateline) . "";
        } else {
            return date('Y-m-d', $dateline);
        }
    }
}

if (!function_exists('get_url_video')) {
    function get_url_video( $video )
    {
        $vid = trim(strrchr($video, '/'), '/');
        $vid = substr($vid, 0, -5);
        $json = file_get_contents("http://vv.video.qq.com/getinfo?vids=" . $vid . "&platform=101001&charge=0&otype=json");
//             echo $json;die;
        $json = substr($json, 13);
        $json = substr($json, 0, -1);
        $a = json_decode(html_entity_decode($json));
        $sz = json_decode(json_encode($a), true);
        // print_R($sz);die;
        $url = $sz['vl']['vi']['0']['ul']['ui']['3']['url'];
        $fn = $sz['vl']['vi']['0']['fn'];
        $fvkey = $sz['vl']['vi']['0']['fvkey'];
        $url = $url . $fn . '?vkey=' . $fvkey;
        return $url;
    }

}

/*
 * array转xml
 * @param is_dep false 针对的是浅层数组  true 针对深层数组
 * */
if (!function_exists('array_xml')) {
    function array_xml( $data, $is_dep = false )
    {
        if (!is_array($data)) {
            return false;
        }
        if ($is_dep == false) {
            $xml = "<xml>";
            foreach ($data as $key => $val) {
                if (is_numeric($val)) {
                    $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
                } else {
                    $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
                }
            }
            $xml .= "</xml>";
            return $xml;

        } else {
            return arr2xml($data);
        }
    }
}

/*
 * 深层array转xml
 * */
if (!function_exists('arr2xml')) {
    function arr2xml( $data, $root = true )
    {
        $str = "";
        if ($root) $str .= "<xml>";
        foreach ($data as $key => $val) {
            if (is_array($val)) {
                $child = arr2xml($val, false);
                $str .= "<$key>$child</$key>";
            } else {
                $str .= "<$key><![CDATA[$val]]></$key>";
            }
        }
        if ($root) $str .= "</xml>";
        return $str;
    }

}


/**
 * 将xml转为array
 * @param  string $xml xml字符串或者xml文件名
 * @param  bool $isfile 传入的是否是xml文件名
 * @return array    转换得到的数组
 */
function xml_array( $xml, $isfile = false )
{
    //禁止引用外部xml实体
    libxml_disable_entity_loader(true);
    if ($isfile) {
        if (!file_exists($xml)) return false;
        $xmlstr = file_get_contents($xml);
    } else {
        $xmlstr = $xml;
    }
    $result = json_decode(json_encode(simplexml_load_string($xmlstr, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $result;
}


/**
 * 计算两点地理坐标之间的距离
 * @param  float $longitude1 起点经度
 * @param  float $latitude1 起点纬度
 * @param  float $longitude2 终点经度
 * @param  float $latitude2 终点纬度
 * @param  Int $unit 单位 1:米 2:公里
 * @param  Int $decimal 精度 保留小数位数
 * @return float
 */
if (!function_exists('getDistance')) {
    function getDistance( $longitude1, $latitude1, $longitude2, $latitude2, $unit = 2, $decimal = 2 )
    {
        $EARTH_RADIUS = 6370.996; // 地球半径系数
        $PI = 3.1415926;

        $radLat1 = $latitude1 * $PI / 180.0;
        $radLat2 = $latitude2 * $PI / 180.0;

        $radLng1 = $longitude1 * $PI / 180.0;
        $radLng2 = $longitude2 * $PI / 180.0;

        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;

        $distance = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $distance = $distance * $EARTH_RADIUS * 1000;

        if ($unit == 2) {
            $distance = $distance / 1000;
        }

        return round($distance, $decimal);
    }
}

//echo getDistance(113.625368 , 34.7466 , 113.76985, 34.76984);


function msectime()
{
    list($msec, $sec) = explode(' ', microtime());
    $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    return $msectime;
}




