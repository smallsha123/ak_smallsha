<?php
namespace  Smallsha\Rabbitmq;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Smallsha\Common\SmallshaException;
require_once dirname(__FILE__).'/../../../../autoload.php';
class Receive {

    //创建连接
    protected $conn;

    //创建channel
    protected $channel;

    //创建交换机
    protected $ex;

    //创建队列
    protected $queue;

    //连接数据库

    //new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    public function __construct($host='127.0.0.1',$port='5672',$username='smallsha',$password='smallsha'){
        $msyql_config=array(
            'dsn'=>'mysql:host=127.0.0.1;dbname=laravel',
            'username'=>'root',
            'password'=>'smallsha'
        );
        try{
            $this->connection = new AMQPStreamConnection($host, $port, $username, $password);
            $this->channel = $this->connection->channel();
            $this->setChannel();
        }catch(SmallshaException $e){
            throw $e;
        }
	}

    public function setChannel(){
        $this->channel->queue_declare('hello', false, false, false, false);
        echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
        $callback = function($msg) {
            $this->PdoDb = new \Smallsha\Classes\PdoDb();
            $_data = json_decode($msg->body,true);
            try{
                $this->PdoDb->insert('sm_chat_record',['fid'=>$_data['fid'],'tid'=>$_data['tid'],'msg'=>$_data['msg'],'addtime'=>$_data['addtime']]);
		
            }catch (\PDOException $e){
                echo $e->getMessage();
            }

        };
        $this->channel->basic_consume('hello', '', false, true, false, false, $callback);
        while(count($this->channel->callbacks)) {
            $this->channel->wait();
        }
        $this->PdoDb->close();
        $this->channel->close();
        $this->connection->close();
    }
}
//运行消费者
new Receive();
