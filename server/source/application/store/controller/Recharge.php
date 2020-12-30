<?php

namespace app\store\controller;

use app\store\model\Recharge as RechargeModel;

/**
 * 订单管理
 * Class Order
 * @package app\store\controller
 */
class Recharge extends Controller
{

    public function index()
    {
        $model = new RechargeModel;
        $list = $model->getList();
        return $this->fetch('index', compact('list'));
    }
}