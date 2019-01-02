<?php 
namespace  Smallsha\Classes;

class Pay{
    const QUERY_ORDER =  'https://api.mch.weixin.qq.com/pay/orderquery';

	public function __construct($pay_param = ''){
        if(!empty($pay_param)){
            $this->appid=$pay_param['appid'];
            $this->appsecret=$pay_param['appsecret'];

            $this->mkey=$pay_param['mkey'];

            $this->mch_id=$pay_param['mchid'];
        }
	}

	 //获取预支付交易会话标识，有效期两个小时
	    function get_prepay_id($total_fee,$out_trade_no,$notify_url,$pay_id = '模板商品',$good_name = '模板商品'){
	        $nonce_str = $this->createNonceStr();
	        $sign = $this->signjiami($nonce_str,$total_fee,$out_trade_no,$notify_url,$pay_id,$good_name);

	        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
	        $data = "<xml>
			   <appid>".$this->appid."</appid>
			   <attach>".$pay_id."</attach>
			   <body>".$good_name."</body>
			   <mch_id>".$this->mch_id."</mch_id>
			   <nonce_str>".$nonce_str."</nonce_str>
			   <notify_url>".$notify_url."</notify_url>
			   <out_trade_no>".$out_trade_no."</out_trade_no>
			   <spbill_create_ip>114.215.135.83</spbill_create_ip>
			   <total_fee>".$total_fee."</total_fee>
			   <trade_type>NATIVE</trade_type>
			   <sign>".$sign."</sign>
			</xml>";
		 $result = sm_request($url,$data);//dump($result);
//            header("Content-type:text/xml");
//           echo $result;die;

		 $postObj = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
             $code_url = trim($postObj->code_url);
	       	return $code_url;
	    }

	    public  function query_order($out_trade_no){
            $this->params['appid'] = $this->appid;
            $this->params['mch_id'] = $this->mch_id;
            $this->params['nonce_str'] = $this->createNonceStr();
            $this->params['out_trade_no'] = $out_trade_no;
            $this->sign = $this->MakeSign($this->params);

            $data = "<xml>
               <appid>".$this->appid."</appid>
               <mch_id>".$this->mch_id."</mch_id>
               <nonce_str>".$this->params['nonce_str']."</nonce_str>
               <out_trade_no>".$this->params['out_trade_no']."</out_trade_no>
               <sign>".$this->sign."</sign>
            </xml>";
            $result = sm_request(self::QUERY_ORDER,$data);
            $postObj = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
            $response = json_decode(json_encode($postObj),true);
            return $response;
        }

        public function MakeSign( $params ){
            ksort($params);
            $string = $this->ToUrlParams($params);
            $string = $string . "&key=".$this->mkey;
            $string = md5($string);
            $result = strtoupper($string);
            return $result;
        }
    /**
     * 将参数拼接为url: key=value&key=value
     * @param $params
     * @return string
     */
    public function ToUrlParams( $params ){
        $string = '';
        if( !empty($params) ){
            $array = array();
            foreach( $params as $key => $value ){
                $array[] = $key.'='.$value;
            }
            $string = implode("&",$array);
        }
        return $string;
    }

	    //生成长度16的随机字符串
	public function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
		    $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return "z".$str;
	}


	public function signjiami($nonce_str,$total_fee,$out_trade_no,$notify_url,$pay_id,$good_name){
	    $string1 = "appid=".$this->appid."&attach=".$pay_id."&body=".$good_name."&mch_id=".$this->mch_id."&nonce_str=".$nonce_str."&notify_url=".$notify_url."&out_trade_no=".$out_trade_no."&spbill_create_ip=114.215.135.83&total_fee=".$total_fee."&trade_type=NATIVE";
	    $result = md5($string1."&key=".$this->mkey);
	    return strtoupper($result);
	}


}




 ?>