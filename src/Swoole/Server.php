<?php
namespace Smallsha\Swoole;
use Smallsha\Common\SmallshaException;
use Smallsha\Swoole\ServerTable;
use  Smallsha\Rabbitmq\Send;
require_once dirname(__FILE__).'/../../../../autoload.php';
class Server  implements \Smallsha\Contract\ServerFace
{
    use ServerTable;
    private $message = null;

    private $ws = null;

    private $usr_array = array();
    //登录用户信息保存redis键
    private $user_hash = "user_hash";

    private $conn_hash = "conn_hash";

    private $redis_conn = null;

    //swoole-table
    private $user= null;

    private  $rabbitmq;

    public function __construct()
    {
        $this->connectRedis();
        $this->rabbitmq = new \Smallsha\Rabbitmq\Send();
        try{
            /*创建swoole_table数据表开始*/
            $this->_init();
            $field = [
                ['key' => 'userid', 'type' => 'string', 'len' => 15],
            ];
            $this->user = new \swoole_table(1024);
            $this->column($this->user,$field);
            $this->user->create();
            //            $this->user->memorySize;  // create 查看表的大小为字节
            /*创建swoole_table数据表结束*/
        }catch (SmallshaException $e){
            throw $e;
        }


        //创建websocket服务器对象，监听0.0.0.0:8000端口
        $this->ws = new \swoole_websocket_server("0.0.0.0", 9508);
        $this->ws->set([
//            'daemonize'=>1,  //后台进程
            'dispatch_mode' => 5,
        ]);

        //监听WebSocket连接打开事件
        /*
         *用户打开连接将$request->fd 保存到user中 key 为用户id  valuefd
         * */
        $this->ws->on('open', function ( $ws, $request ) {
            $data_json = json_decode($request->data, true);
		echo $request->fd;
//            $this->user->set('K_1',['userid'=>$request->fd]);
//            $this->record('user','K_1');
//            print_r($this->record('user'));
//            print_r($this->user->get('K_1'));
//            echo 'connect wait .....  success 欢迎用户连接成功'.$request->fd;
        });

        //监听WebSoc:ket消息事件
        $this->ws->on('message', function ( $ws, $obj ) {

            $Data = json_decode($obj->data,true);//接受客户端发来的用户信息
//            print_r($Data);
            $fd = $obj->fd; //当前连接的fd;
//            echo $fd;
            $ws->bind($fd,$Data['fid']);

            if( isset($Data['msg'])){
//    echo '12312';
                $form_id = $this->user->get($Data['fid'])['userid'];    //发送fid
                $to_id = $this->user->get($Data['tid'])['userid'];    //接受tid
                $bind_user = $ws->connection_info($to_id);
//                print_r($bind_user);
                if($bind_user){
                    echo '456';
                    $user_record = ['form_id'=>$Data['fid'],'to_id'=>$Data['tid'],'msg'=>$Data['msg']];
                    $ws->push($to_id, json_encode($user_record));
                }
             $this->rabbitmq->setChannel(json_encode(['fid'=>$Data['fid'],'tid'=>$Data['tid'],'msg'=>$Data['msg'],'addtime'=>time()]));

            } else {
                // 首次接入
                if($this->user->exist($Data['fid'])){
                  //  echo '123';
                    $this->user->del($Data['fid']);
                    $this->user->set($Data['fid'],['userid'=>$fd]);
                }else{
                    echo $Data['fid'] . '/' . $fd;
                    $this->user->set($Data['fid'],['userid'=>$fd]);
                }
            }
        });

        //监听WebSocket连接关闭事件
        $this->ws->on('close', function ( $ws, $fd ) {
            if($this->user->exist($fd)) {
               echo 'exit system';
                $this->user->del($fd);
                $ws->close($fd);
            }
//            echo "client-{$fd} is closed\n";
//            //刷新好友列表
//            $this->redis_conn->hDel($this->conn_hash, $fd);
//            $this->send(true, 0, $this->echoMsg(4, '', $this->all_in_line()));

        });

        $this->ws->start();

    }

    public function connectRedis($host = '127.0.0.1',$port = '6379'){
        try{
            $this->redis_conn = new \Redis();
            $this->redis_conn->connect($host,$port);
        }catch (\Exception $e){
            exit($e->getMessage());
        }

    }


    private function register( $id )
    {
        # phone,username,password 检测注册信息不得为空

        if (!$this->checkArray($this->message[0])) {
            $this->send(false, $id, $this->echoMsg(1000, 'have null data!', ''));
            return;
        }

        $user_phone = $this->message[0]['phone'];

        $user_array = array(
            "password" => $this->message[0]['password'],
            "username" => $this->message[0]['username']
        );

        if (!empty($this->getHash($this->user_hash, $user_phone))) {
            $this->send(false, $id, $this->echoMsg(1000, 'this phone is register!', ''));
        } else {
            $this->setHash($this->user_hash, $user_phone, serialize($user_array));
            $this->send(false, $id, $this->echoMsg(3, '', ''));
        }
    }

    private function login( $id )
    {
        $psd = $this->message[0]['psd'];
        $usr = $this->message[0]['usr'];

        $in_res = $this->getHash($this->user_hash, $usr);
        if (empty($in_res)) {
            $this->send(false, $id, $this->echoMsg(1000, 'user not exist,please register!', $this->message));
        } else {
            $in_res = unserialize($in_res);
            if ($in_res['password'] == $psd) {

                //save user info
                $this->setHash($this->conn_hash, $id, $in_res['username']);

                $this->send(false, $id, $this->echoMsg(1, array("id" => $id, "username" => $in_res['username']), ''));

                //change friends item
                $this->send(true, 0, $this->echoMsg(4, '', $this->all_in_line()));
            } else {
                $this->send(false, $id, $this->echoMsg(1000, 'password is error!', ''));
            }
        }
    }

    //search all in line
    private function all_in_line()
    {
        $res = $this->redis_conn->hGetAll($this->conn_hash);
        return array($res);
    }


    // send msg
    private function send( $is_all = false, $id = 0, $msg = '' )
    {
        if ($is_all) {
            foreach ($this->ws->connections as $fd) {
                $this->ws->push($fd, $msg);
            }
        } else {
            $this->ws->push($id, $msg);
        }
    }

    private function sendMsg()
    {
        $send_id = intval($this->message[0]['to']);

        if ($send_id != 0) {
            //person

            $this->send(false, $send_id, $this->echoMsg(2, '', $this->message[0]));

        } else {
            //all
            $this->send(true, 0, $this->echoMsg(2, '', $this->message[0]));
        }

    }


    //返回json字符串
    private function echoMsg( $code, $msg, $data )
    {
        $redata = array();
        //$data不为空
        if (!empty($data)) {
            if (is_array($data)) {//传入数组
                $redata = $data;
            } else {//传入字符串
                $redata = $data;
            }
        }
        if ($code == 0) {//识别码为0
            $reMsg = array('code' => $code, 'msg' => $msg);
            return json_encode($reMsg);
        } else {//识别码不为0
            $reMsg = array(
                'code' => $code,
                'msg' => $msg,
                'data' => $redata
            );
            return json_encode($reMsg);
        }
    }

    //save login state
    public function setHash( $data = '', $key = '', $value = '' )
    {
        # hash
        $in_login = $this->redis_conn->hGet($data, $key);
        if (empty($in_login)) {
            $this->redis_conn->hSet($data, $key, $value);
            return true;
        } else {
            return false;
        }
    }

    public function getHash( $data = '', $key = '' )
    {
        return $this->redis_conn->hGet($data, $key);
    }

    public function checkArray( $data_array )
    {
        foreach ($data_array as $key => $value) {
            if (empty($value)) {
                return false;
            }
        }
        return true;
    }

}

new Server();

