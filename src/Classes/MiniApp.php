<?php
namespace  Smallsha\Classes;


class MiniApp {
    const WXLOGIN = "https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code";

    private $appid;
    private $secret;
    private $code;
    private $url;

    private $sessionkey;
    /**
     * error code 说明.
     * <ul>

     *    <li>-41001: encodingAesKey 非法</li>
     *    <li>-41003: aes 解密失败</li>
     *    <li>-41004: 解密后得到的buffer非法</li>
     *    <li>-41005: base64加密失败</li>
     *    <li>-41016: base64解密失败</li>
     * </ul>
     */
    public static $OK = 0;
    public static $IllegalAesKey = -41001;
    public static $IllegalIv = -41002;
    public static $IllegalBuffer = -41003;
    public static $DecodeBase64Error = -41004;

    public function __construct($appid,$secret)
    {
        $this->appid = $appid;
        $this->secret = $secret;
    }


    public function setCode($code){
        $this->code = $code;
        return $this;
    }


    public function getSessionKey(){
        $this->url = sprintf(self::WXLOGIN,$this->appid,$this->secret,$this->code);
        $result = sm_request($this->url);
        return json_decode($result,true);
    }


    public function setSessionKey($sessionkey){
        $this->sessionKey  = $sessionkey;
    }

    public function getToken(){
        $v = 1;
        $key = mt_rand();
        $hash = hash_hmac("sha1", $v . mt_rand() . get_time(), $key, true);
        $token = str_replace('=', '', strtr(base64_encode($hash), '+/', '-_'));
        return $token;
    }


    /**
     * 检验数据的真实性，并且获取解密后的明文.
     * @param $encryptedData string 加密的用户数据
     * @param $iv string 与用户数据一同返回的初始向量
     * @param $data string 解密后的原文
     *
     * @return int 成功0，失败返回对应的错误码
     */
    public function decryptData( $encryptedData, $iv, &$data )
    {
        if (strlen($this->sessionKey) != 24) {
            return static::$IllegalAesKey;
        }
        $aesKey=base64_decode($this->sessionKey);


        if (strlen($iv) != 24) {
            return static::$IllegalIv;
        }
        $aesIV=base64_decode($iv);

        $aesCipher=base64_decode($encryptedData);

        $result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

        $dataObj=json_decode( $result );
        if( $dataObj  == NULL )
        {
            return static::$IllegalBuffer;
        }
        if( $dataObj->watermark->appid != $this->appid )
        {
            return static::$IllegalBuffer;
        }
        $data = $result;
        return static::$OK;
    }




}