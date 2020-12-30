<?php

namespace app\api\model;

use app\common\model\ShopOrder as ShopOrderModel;

use think\Db;

class ShopOrder extends ShopOrderModel
{

    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'wxapp_id',
    ];

    public  function createOrder($user,$data) 
    {
        $result = true;
        Db::startTrans();
        try {
            $data = [
                'user_id' => $user['user_id'],
                'shop_id' => $data['shop_id'],
                'points' => $data['points'],
                'status' => 10,//支付成功
                'create_time' => time(),
                'wxapp_id' => self::$wxapp_id,
                'order_sn' => $this->createOrderSn(),
            ];
            $ins_res = $this->allowField(true)->save($data);
            if (!$ins_res) {
                throw new \Exception("订单创建失败");
            }
            $user_change = Db::name('user')->where('user_id', $user['user_id'])->setDec('current_points', $data['points']);
            if (!$user_change) {
                throw new \Exception("积分扣除失败");
            }
            $log = [
                'type' => 0,
                'create_type' => 1,
                'wxapp_id' => self::$wxapp_id,
                'mode' => 'dec',
                'money' => $data['points'],
                'remark' => '订单：'.$data['order_sn'].'使用积分',
                'create_time' => $data['create_time'],
                'user_id' => $data['user_id']
            ];

            Db::name('recharge')->insert($log);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            $result = false;
            $this->error = $e->getMessage();
            Db::rollback();
        }

        return $result;
        
    }


    public function createOrderSn()
    {
        mt_srand((double) microtime() * 10000);
        $order_sn = date('ymdHi').str_pad(mt_rand(1,99999),5,'0',STR_PAD_LEFT);
        return $order_sn;
    }

}
