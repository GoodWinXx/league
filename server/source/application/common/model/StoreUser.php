<?php

namespace app\common\model;

use think\Request;
/**
 * 商家用户模型
 * Class StoreUser
 * @package app\common\model
 */
class StoreUser extends BaseModel
{
    protected $name = 'store_user';


    /**
     * 添加新记录
     * @param $data
     * @return false|int
     */
    public function add($data)
    {
        $nick_uni = $this->checkUniqueNickname($data['user_name'],1);
        if (!$nick_uni) {
            $this->error = '用户名重复';
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
        $data['password'] = trim($data['password']);
        $data['password'] = trim($data['password']);
        if ($data['password'] !== $data['password_confirm']) {
            $this->error = '确认密码不正确';
            return false;
        }
        if (!empty($data['password'])) {
            $data['password'] = yoshop_hash($data['password']);
        }
        if (!empty($data['shop_ids'])) {
            $data['shop_ids'] = json_encode($data['shop_ids']);
        }
        if (!empty($data['role_ids'])) {
            $data['role_ids'] = json_encode($data['role_ids']);
        }
        $data['wxapp_id'] = self::$wxapp_id;
        return $this->allowField(true)->save($data);
    }

    /**
     * 编辑记录
     * @param $data
     * @return bool|int
     */
    public function edit($data)
    {
        $nick_uni = $this->checkUniqueNickname($data['user_name']);
        if (!$nick_uni) {
            $this->error = '用户名重复';
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
        $data['password'] = trim($data['password']);
        $data['password'] = trim($data['password']);
        if ($data['password'] !== $data['password_confirm']) {
            $this->error = '确认密码不正确';
            return false;
        }
        if (!empty($data['password'])) {
            $data['password'] = yoshop_hash($data['password']);
        }
        if (!empty($data['shop_ids'])) {
            $data['shop_ids'] = json_encode($data['shop_ids']);
        }
        if (!empty($data['role_ids'])) {
            $data['role_ids'] = json_encode($data['role_ids']);
        }
        return $this->allowField(true)->save($data);
    }

    public function getList($map=[])
    {   
        $request = Request::instance();
        return $this->where($map)->order(['create_time' => 'desc'])
            ->paginate(15, false, ['query' => $request->request()]);
    }

    public function checkUniqueMobile($mobile,$is_add=0)
    {
        if ($is_add) {
            $user = $this->where('mobile',$mobile)->find();
        } else {
            $user = $this->where('mobile',$mobile)->where('store_user_id','<>',$this->store_user_id)->find();
        }
        
        return $user ? false : true;
    }

    public function checkUniqueNickname($nickName,$is_add=0)
    {
        if ($is_add) {
            $user = $this->where('user_name',$nickName)->find();
        } else {
            $user = $this->where('user_name',$nickName)->where('store_user_id','<>',$this->store_user_id)->find();
        }
        
        return $user ? false : true;
    }


    /**
     * 关联微信小程序表
     * @return \think\model\relation\BelongsTo
     */
    public function wxapp() {
        return $this->belongsTo('Wxapp');
    }

    /**
     * 新增默认商家用户信息
     * @param $wxapp_id
     * @return false|int
     */
    public function insertDefault($wxapp_id)
    {
        return $this->save([
            'user_name' => 'yoshop_' . $wxapp_id,
            'password' => md5(uniqid()),
            'wxapp_id' => $wxapp_id,
        ]);
    }

}
