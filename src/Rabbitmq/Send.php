<?php

namespace Smallsha\Rabbitmq;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Smallsha\Common\SmallshaException;

require_once dirname(__FILE__) . '/../../../../autoload.php';

class Send
{
    //创建连接
    protected $conn;

    //创建channel
    protected $channel;

    //创建交换机
    protected $ex;

    //创建队列
    protected $queue;

    public function __construct( $host = '127.0.0.1', $port = '5672', $username = 'smallsha', $password = 'smallsha' )
    {
        try {
            $this->connection = new AMQPStreamConnection($host, $port, $username, $password);
            $this->channel = $this->connection->channel();
        } catch (SmallshaException $e) {
            throw $e;
        }
    }

    public function setChannel( $msg )
    {
        $this->channel->queue_declare('hello', false, false, false, false);


                $msg = new AMQPMessage($msg);
                $this->channel->basic_publish($msg, '', 'hello');

            echo " [x] Sent 'Hello World!###/.'\n";

//        $this->channel->close();
//        $this->channel->close();
    }
}

new Send();

