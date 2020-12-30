<?php

namespace app\store\model;

use app\common\model\Recharge as RechargeModel;
use think\Session;
use think\Request;
/**
 * 商品分类模型
 * Class Category
 * @package app\store\model
 */
class Recharge extends RechargeModel
{

    public function getOpNameAttr($value,$data)
    {
        if ($data['op_id'] == 0) {
            return '';
        }
        $user = StoreUser::get($data['op_id']);
        return $user ? $user->user_name : '';
    }

    public function getNickNameAttr($value,$data)
    {
        if ($data['user_id'] == 0) {
            return '';
        }
        $user = User::get($data['user_id']);
        return $user ? $user->nickName : '';
    }

    public function getRealNameAttr($value,$data)
    {
        if ($data['user_id'] == 0) {
            return '';
        }
        $user = User::get($data['user_id']);
        return $user ? $user->real_name : '';
    }

    public function getList()
    {
        $request = Request::instance();
        $lists = $this->order(['create_time' => 'desc'])
            ->paginate(15, false, ['query' => $request->request()]);
        return $lists;
    }

    /**
     * 添加新记录
     * @param $data
     * @return false|int
     */
    public function add($data)
    {
        $admin = Session::get('yoshop_store.user');
        $data['op_id'] = $admin['store_user_id'];
        $data['create_time'] = time();
        $data['wxapp_id'] = self::$wxapp_id;
        return $this->allowField(true)->save($data);
    }

}