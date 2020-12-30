<?php

namespace app\api\controller\user;

use app\api\controller\Controller;
use app\api\model\Order as OrderModel;
use think\Db;
/**
 * 个人中心主页
 * Class IndexModel
 * @package app\api\controller\user
 */
class Index extends Controller
{
    /**
     * 获取当前用户信息
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function detail()
    {
        // 当前用户信息
        $userInfo = $this->getUser();
        // pre($userInfo);
        $order_type = $userInfo['is_bind'] ? 'shop_order' : 'shop_order';
        $account_type = $userInfo['is_bind'] ? 'account' : 'bind';

        // 订单总数
        $model = new OrderModel;
        $orderCount = [
            'payment' => $model->getCount($userInfo['user_id'], 'payment'),
            'received' => $model->getCount($userInfo['user_id'], 'received'),
        ];
        return $this->renderSuccess(compact('userInfo', 'orderCount','order_type','account_type'));
    }


    public function bind($mobile,$auth_code)
    {
        $user = Db::name('user')->where(['mobile'=>trim($mobile),'auth_code'=>trim($auth_code)])->find();
        if (!$user) {
            return $this->renderError('手机号或者授权码不正确');
        }

        if ($user['is_bind']) {
            return $this->renderError('用户已被绑定');
        }

        $userInfo = $this->getUser();
        $data = [
            'open_id' => $userInfo['open_id'],
            'nickName' => $userInfo['nickName'],
            'avatarUrl' => $userInfo['avatarUrl'],
            'gender' => $userInfo['gender'] == '男' ? 1 : 2,
            'country' => $userInfo['country'],
            'province' => $userInfo['province'],
            'city' => $userInfo['city'],
            'is_bind' => 1,
        ];
        $res = Db::name('user')->where('user_id',$user['user_id'])->update($data);
        if ($res) {
            Db::name('user')->delete($userInfo['user_id']);
        }
        return $this->renderSuccess([],'用户绑定成功');

    }

}
