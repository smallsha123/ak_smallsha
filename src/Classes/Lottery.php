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
    public function __construct($data=[],$win_key )
    {
        foreach($data as $k => $v){
            $arr[$v['id']] = $v[$win_key];
        }
        $this->dataList = $arr;
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
                $this->pushData['win_key'] = $key;  //获得奖品的ID
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset($this->dataList[$this->pushData['win_key']]);
        $this->pushData['fail_key'] = array_keys($this->dataList);
        return $this->pushData;
    }
}
?>
