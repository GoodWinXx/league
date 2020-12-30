<?php
namespace app\store\controller;

use think\Db;
/**
 * 后台首页
 * Class IndexModel
 * @package app\store\controller
 */
class Index extends Controller
{
    public function index()
    {
        $near_day = $this->getWeekDay();
        $result = ['sale_money'=>[],'sale_count'=>[]];
        foreach ($near_day['time_start'] as $k=>$v) {
            $data = Db::name('shop_order')->where(['create_time'=>['between',[$v,$near_day['time_end'][$k]]]])->column('points','id');
            $result['sale_money'][] = array_sum($data);
            $result['sale_count'][] = count($data);
        }
        $timeline = json_encode($near_day['day']);
        return $this->fetch('index',compact('timeline','result'));
    }

    private function getWeekDay($length=7)
    {
        $days = ['day'=>[],'time_start'=>[],'time_end'=>[]];
        $start_at = strtotime(date('Y-m-d',strtotime('-'.($length-1).' day')));
        for($i=0;$i<$length;$i++) {
            $days['time_start'][$i] = $start_at + 86400*$i;
            $days['time_end'][$i] = $days['time_start'][$i] + 86400-1;
            $days['day'][$i] = date('Y-m-d',$days['time_start'][$i]);
        }
        return $days;
    }

    public function demolist()
    {
        return $this->fetch('demo-list');
    }


}
