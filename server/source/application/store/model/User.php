<?php

namespace app\store\model;

use app\common\model\User as UserModel;
use think\Cache;
use think\Db;
/**
 * 用户模型
 * Class User
 * @package app\store\model
 */
class User extends UserModel
{

    /**
     * 添加新记录
     * @param $data
     * @return false|int
     */
    public function add($data)
    {
        $nick_uni = $this->checkUniqueNickname($data['nickName'],1);
        if (!$nick_uni) {
            $this->error = '昵称重复';
            return false;
        }
        $mobile_check = check_mobile($data['mobile']);
        if (!$mobile_check) {
            $this->error = '手机不合法';
            return false;
        }
        $mobile_uni = $this->checkUniqueMobile($data['mobile'],1);
        if (!$mobile_uni) {
            $this->error = '手机号重复';
            return false;
        }
        if (!empty($data['password'])) {
            $data['password'] = yoshop_hash($data['password']);
        }
        $data['wxapp_id'] = self::$wxapp_id;
        $data['auth_code'] = $this->randomStr(4);
        $this->deleteCache();
        return $this->allowField(true)->save($data);
    }

    public function randomStr($length)
    {
        $random_arr = array_merge(range('a','z'),range('A','Z'));
        $str = '';
        $counts = count($random_arr);
        for ($i=0;$i<$length;$i++) {
            $rand = mt_rand(0,$counts-1);
            $str .= $random_arr[$rand];
        }

        return $str;
    }

    /**
     * 编辑记录
     * @param $data
     * @return bool|int
     */
    public function edit($data)
    {
        $nick_uni = $this->checkUniqueNickname($data['nickName']);
        if (!$nick_uni) {
            $this->error = '昵称重复';
            return false;
        }
        $mobile_check = check_mobile($data['mobile']);
        if (!$mobile_check) {
            $this->error = '手机不合法';
            return false;
        }
        $mobile_uni = $this->checkUniqueMobile($data['mobile']);
        if (!$mobile_uni) {
            $this->error = '手机号重复';
            return false;
        }
        if (!empty($data['password'])) {
            $data['password'] = yoshop_hash($data['password']);
        }
        $this->deleteCache();
        return $this->allowField(true)->save($data);
    }

    /**
     * 删除商品分类
     * @param $user_id
     * @return bool|int
     */
    public function remove($user_id)
    {
        // // 判断是否存在商品
        // if ($goodsCount = (new Goods)->where(compact('category_id'))->count()) {
        //     $this->error = '该分类下存在' . $goodsCount . '个商品，不允许删除';
        //     return false;
        // }
        // // 判断是否存在子分类
        // if ((new self)->where(['parent_id' => $category_id])->count()) {
        //     $this->error = '该分类下存在子分类，请先删除';
        //     return false;
        // }
        $this->deleteCache();
        return $this->delete();
    }


    public function checkUniqueMobile($mobile,$is_add=0)
    {
        if ($is_add) {
            $user = $this->where('mobile',$mobile)->find();
        } else {
            $user = $this->where('mobile',$mobile)->where('user_id','<>',$this->user_id)->find();
        }
        
        return $user ? false : true;
    }

    public function checkUniqueNickname($nickName,$is_add=0)
    {
        if ($is_add) {
            $user = $this->where('nickName',$nickName)->find();
        } else {
            $user = $this->where('nickName',$nickName)->where('user_id','<>',$this->user_id)->find();
        }
        
        return $user ? false : true;
    }

    /**
     * 删除缓存
     * @return bool
     */
    private function deleteCache()
    {
        return Cache::rm('user_' . self::$wxapp_id);
    }

    public function recharge($data)
    {
        Db::startTrans();
        try {
            $rechargeModel = new Recharge;
            $data['user_id'] = $this->user_id;
            // $data['type'] = 0;
            
            switch ($data['mode']) {
                case 'inc':
                    $data['money'] = $data['money'];
                    $this->total_points += $data['money'];
                    break;
                case 'dec':
                    $data['money'] = -$data['money'];
                    break;
                default:
                    $data['money'] = $data['money'];
                    $this->total_points += $data['money'];
                    break;
            }
            $this->current_points += $data['money'];
            $res = $rechargeModel->add($data);
            if (!$res) {
                throw new \Exception("积分明细插入失败");
            }
            $res = $this->save();
            if (!$res) {
                throw new \Exception("用户账户修改失败");
            }
            // 提交事务
            Db::commit();
            return true;
        } catch (\Exception $e) {
            // 回滚事务
            $this->error = $e->getMessage();
            Db::rollback();
        }

        return false;
    }
}
