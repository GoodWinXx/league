<?php

namespace app\store\model;

use app\common\model\ShopOrder as ShopOrderModel;
use think\Session;
use think\Request;
use think\Db;
class ShopOrder extends ShopOrderModel
{


    public function getLists($map=[])
    {
        $request = Request::instance();
        $lists = $this->where($map)->order(['id' => 'desc'])
            ->paginate(15, false, ['query' => $request->request()]);
        return $lists;
    }


    public function getShopLists($map=[])
    {
        $request = Request::instance();
        $lists = $this->where($map)->group('shop_id')->order(['id' => 'desc'])
            ->paginate(15, false, ['query' => $request->request()]);
        $shop_names = Db::name('shop')->column('shop_name','id');
        if ($lists) {
            foreach ($lists as $k=>&$v) {
                $v['all_points'] = Db::name('shop_order')->where($map)->where('shop_id',$v['shop_id'])->sum('points');
                $v['shop_name'] = $shop_names[$v['shop_id']];
            }
        }

        return $lists;
    }
}