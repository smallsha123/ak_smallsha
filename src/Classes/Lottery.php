<?php
namespace  Smallsha\Classes;


class Lottery
{

    public $dataList;
    public $wining_key;
    public $pushData = [];

    /*$prize_arr = array(    // $dataList数据格式
    '0' => array('id'=>1,'prize'=>'平板电脑','v'=>1),
    '1' => array('id'=>2,'prize'=>'数码相机','v'=>5),
    '2' => array('id'=>3,'prize'=>'音箱设备','v'=>10),
    '3' => array('id'=>4,'prize'=>'4G优盘','v'=>12),
    '4' => array('id'=>5,'prize'=>'10Q币','v'=>22),
    '5' => array('id'=>6,'prize'=>'下次没准就能中哦','v'=>50),
    );*/
    public function __construct( $data )
    {
        $this->dataList = $data;
    }


    //抽奖算法
    public function getDataRand()
    {
        //概率数组的总概率精度
        $proSum = array_sum($this->dataList);
        //概率数组循环
        foreach ($this->dataList as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $this->wining_key = $key;  //获得奖品的ID
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        return $this;
    }

    //
    public function pushData(){
        $this->pushData['win_data'] = $this->dataList[$this->wining_key];
        unset($this->dataList[$this->wining_key]);
        foreach($this->dataList as $k=>$v){
            $this->pushData['fail_data'] = $v;
        }
        return $this->pushData;
    }
}

?>

