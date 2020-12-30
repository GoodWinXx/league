<?php

namespace app\store\controller;

use app\store\model\User as UserModel;
use app\store\model\ShopOrder as ShopOrderModel;
use app\store\model\Shop as ShopModel;
use think\Cache;
use think\Session;
use think\Db;
/**
 * 数据管理
 * Class User
 * @package app\store\controller
 */
class Data extends Controller
{

    protected $shop_ids = [];

    public function _initialize()
    {
        parent::_initialize();
        $this->shop_ids = json_decode(Session::get('yoshop_store.shop_ids'),true);
    }
    /**
     * 业绩汇总
     * @return [type] [description]
     */
    public function index()
    {
        $total_data = [
            'all_points' => Db::name('user')->sum('total_points'),
            'all_users' => Db::name('user')->count(),
            'all_order_counts' => Db::name('shop_order')->where('status',10)->count(),
            'use_points' => Db::name('shop_order')->where('status',10)->sum('points')
        ];
        $today_start_at = strtotime(date('Y-m-d'));
        $now = time();
        $today_map = [$today_start_at,$now];
        $today_orders = Db::name('shop_order')->where('create_time','between',$today_map)->where('status',10)->column('points');
        $today_add_users = Db::name('user')->where('create_time','between',$today_map)->count();
        $today_create_order_users = Db::name('shop_order')->where('create_time','between',$today_map)->where('status',10)->count(' distinct user_id');
        $today_data = [
            'sale_total' => array_sum($today_orders),
            'sale_counts' => count($today_orders),
            'add_users' => $today_add_users,
            'create_order_users' => $today_create_order_users
        ];
        $yestoday_start_at = $today_start_at-3600*24;
        $yes_map = [$yestoday_start_at,$today_start_at];
        $today_orders = Db::name('shop_order')->where('create_time','between',$yes_map)->where('status',10)->column('points');
        $today_add_users = Db::name('user')->where('create_time','between',$yes_map)->count();
        $today_create_order_users = Db::name('shop_order')->where('create_time','between',$yes_map)->where('status',10)->count(' distinct user_id');
        $yestoday_data = [
            'sale_total' => array_sum($today_orders),
            'sale_counts' => count($today_orders),
            'add_users' => $today_add_users,
            'create_order_users' => $today_create_order_users
        ];
       
        return $this->fetch('index', compact('total_data','today_data','yestoday_data'));
    }

    /**
     * 店铺列表
     * @return [type] [description]
     */
    public function shop()
    {
        $model = new ShopOrderModel;
        $map = [];
        if ($this->shop_ids) {
            $map['shop_id'] = ['in',$this->shop_ids];
        }
        $list = $model->getShopLists($map);
        return $this->fetch('shop',compact('list'));
    }


    /**
     * 消费明细
     * @return [type] [description]
     */
    public function lists()
    {
        $map = $search = [];
        $search_param = [
            'start_time' => '',
            'end_time' => '',
            'shop_id' => 0,
            'search' => ''
        ];
        $shop_id = $this->request->param('shop_id',0);
        $search_params = $this->request->param();
        $search_param = array_merge($search_param,$search_params);
        $data_type = !empty($search_param['dataType']) ? $search_param['dataType'] : '';
        if (!empty($search_param['search'])) {
            $user_ids = Db::name('user')->where('nickName|real_name','like','%'.trim($search_param['search'].'%'))->column('user_id');
            if ($user_ids) {
                $map['user_id'] = ['in',$user_ids];
            }
            // pre($map);
        }
        if (!empty($search_param['start_time'])) {
            
            $map['create_time'] = ['gt',strtotime($search_param['start_time'])];
        }
        if (!empty($search_param['end_time'])) {
            
            $map['create_time'] = ['lt',strtotime($search_param['end_time'])+86400];
        }
        $model = new ShopOrderModel;
        $shop_where = [];
        if ($this->shop_ids) {
            $map['shop_id'] = ['in',$this->shop_ids];
            $shop_where['id'] = ['in',$this->shop_ids];
        }
        if ($shop_id) {
            $map['shop_id'] = $shop_id;
        } 

        $search['shops'] = Db::name('shop')->where($shop_where)->select();
        $list = $model->getLists($map);
        
        return $this->fetch('lists',compact('list','shop_id','search','search_param','data_type'));
    }


    /**
     * 明细导出
     * @return [type] [description]
     */
    public function listsExport()
    {
        $map = $search = [];
        $search_param = [
            'start_time' => '',
            'end_time' => '',
            'shop_id' => 0,
            'search' => ''
        ];
        $shop_id = $this->request->param('shop_id',0);
        $search_params = $this->request->param();
        $search_param = array_merge($search_param,$search_params);
        $data_type = !empty($search_param['dataType']) ? $search_param['dataType'] : '';
        if (!empty($search_param['search'])) {
            $user_ids = Db::name('user')->where('nickName|real_name','like','%'.trim($search_param['search'].'%'))->column('user_id');
            if ($user_ids) {
                $map['user_id'] = ['in',$user_ids];
            }
            // pre($map);
        }
        if (!empty($search_param['start_time'])) {
            
            $map['create_time'] = ['gt',strtotime($search_param['start_time'])];
        }
        if (!empty($search_param['end_time'])) {
            
            $map['create_time'] = ['lt',strtotime($search_param['end_time'])+86400];
        }

        if ($shop_id) {
            $map['shop_id'] = $shop_id;
        } 
        $data = Db::name('shop_order')->where($map)->select()->toArray();
        $shops = Db::name('shop')->column('shop_name','id');
        $users = Db::name('user')->column('real_name','user_id');
        foreach ($data as $k=>&$v) {
            $v['shop_name'] = $shops[$v['shop_id']] ?? '';
            $v['real_name'] = $users[$v['user_id']] ?? '';
            $v['create_time'] = date('Y-m-d H:i:s',$v['create_time']);
        }
        // pre($data);
        $headers = [
            '订单流水号',
            '门店',
            '用户名',
            '消费余额',
            '时间'
        ];

        $keys = [
            'order_sn',
            'shop_name',
            'real_name',
            'points',
            'create_time',
        ];
        // pre($data);
        $excel = new \app\common\library\excel\Excel;
        $excel->export('消费明细',$headers,$data,$keys);

    }
}