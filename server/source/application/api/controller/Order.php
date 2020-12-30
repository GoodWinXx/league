<?php

namespace app\api\controller;

use app\api\model\Order as OrderModel;
use app\api\model\Wxapp as WxappModel;
use app\api\model\User as UserModel;
use app\api\model\Cart as CartModel;
use app\api\model\ShopOrder as ShopOrderModel;
use app\common\library\wechat\WxPay;
use think\Db;
/**
 * 订单控制器
 * Class Order
 * @package app\api\controller
 */
class Order extends Controller
{
    /* @var \app\api\model\User $user */
    private $user;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->user = $this->getUser();   // 用户信息
    }

    /**
     * 订单确认-立即购买
     * @param $goods_id
     * @param $goods_num
     * @param $goods_sku_id
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function buyNow($goods_id, $goods_num, $goods_sku_id)
    {
        // 商品结算信息
        $model = new OrderModel;
        $order = $model->getBuyNow($this->user, $goods_id, $goods_num, $goods_sku_id);
        if (!$this->request->isPost()) {
            return $this->renderSuccess($order);
        }
        if ($model->hasError()) {
            return $this->renderError($model->getError());
        }
        // 创建订单
        if ($model->add($this->user['user_id'], $order)) {
            // 发起微信支付
            return $this->renderSuccess([
                'payment' => $this->wxPay($model['order_no'], $this->user['open_id']
                    , $order['order_pay_price']),
                'order_id' => $model['order_id']
            ]);
        }
        $error = $model->getError() ?: '订单创建失败';
        return $this->renderError($error);
    }

    /**
     * 订单确认-购物车结算
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \Exception
     */
    public function cart()
    {
        // 商品结算信息
        $model = new OrderModel;
        $order = $model->getCart($this->user);
        if (!$this->request->isPost()) {
            return $this->renderSuccess($order);
        }
        // 创建订单
        if ($model->add($this->user['user_id'], $order)) {
            // 清空购物车
            $Card = new CartModel($this->user['user_id']);
            $Card->clearAll();
            // 发起微信支付
            return $this->renderSuccess([
                'payment' => $this->wxPay($model['order_no'], $this->user['open_id']
                    , $order['order_pay_price']),
                'order_id' => $model['order_id']
            ]);
        }
        $error = $model->getError() ?: '订单创建失败';
        return $this->renderError($error);
    }

    /**
     * 构建微信支付
     * @param $order_no
     * @param $open_id
     * @param $pay_price
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    private function wxPay($order_no, $open_id, $pay_price)
    {
        $wxConfig = WxappModel::getWxappCache();
        $WxPay = new WxPay($wxConfig);
        return $WxPay->unifiedorder($order_no, $open_id, $pay_price);
    }


    public function shopOrder($shop_id,$points)
    {
        if (!$this->request->isPost()) {
            return $this->renderError('数据有误');
        }
        if (!$shop_id || !$points) {
            return $this->renderError('数据有误');
        }
        $userModel = new UserModel;
        $check_points = $userModel->checkPoints($this->user['user_id'],$points);
        if (!$check_points) {
            return $this->renderError($userModel->getError());
        }
        // 商品结算信息
        $model = new ShopOrderModel;
        $order = $model->createOrder($this->user, ['shop_id'=>$shop_id,'points'=>$points]);
        
        if (!$order) {
            return $this->renderError($model->getError());
        }
        // 创建订单
        if ($order) {
            // 发起微信支付
            $has_points = Db::name('user')->where('user_id',$this->user['user_id'])->value('current_points');
            $result = ['current_points'=>$has_points,'amount'=>$points];
            return $this->renderSuccess($result,'订单支付成功');
        }
        $error = $model->getError() ?: '订单创建失败';
        return $this->renderError($error);
    }


    public function shopOrderLists()
    {
        $userInfo = $this->getUser();
        $orders = Db::name('shop_order')->where(['user_id'=>$userInfo['user_id']])->order('create_time desc')->select()->toArray();
        $shops = Db::name('shop')->column('shop_name','id');
        $status_text = ['0'=> '未支付',10=>'已支付'];
        foreach ($orders as $key=>$val) {
            $orders[$key]['create_time'] = date('Y-m-d H:i:s',$val['create_time']);
            $orders[$key]['shop_name'] = isset($shops[$val['shop_id']]) ? $shops[$val['shop_id']] : '';
            $orders[$key]['status_text'] = isset($status_text[$val['status']]) ? $status_text[$val['status']] : '';
        }
        return $this->renderSuccess(['orders'=>$orders]);
    }

}
