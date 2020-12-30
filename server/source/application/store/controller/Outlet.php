<?php
/**
 * Created by PhpStorm.
 * User: chenweihua
 * Date: 2019/7/6
 * Time: 1:58 PM
 */

namespace app\store\controller;

use app\store\model\Shop as ShopModel;
use think\Db;
use think\Session;

class Outlet extends Controller
{
    public function index()
    {
        $shop_ids = json_decode(Session::get('yoshop_store.shop_ids'),true);
        $shop_id = $shop_ids[0];
        // 模板详情
        $model = ShopModel::get($shop_id);
        if (!$this->request->isAjax()) {
            $region = Db::name('region')->where('id','in',[1170,1171,1172,1173,1174])->select();
            $banners = Db::name('shop_banners')->where('shop_id',$shop_id)->select();
//            dump($banners,$model);exit;
            return $this->fetch('index', compact('model','region','banners'));
        }
        // 更新记录
        if ($model->edit($this->postData('shop'))) {
            return $this->renderSuccess('更新成功', url('outlet/index'));
        }
        $error = $model->getError() ?: '更新失败';
        return $this->renderError($error);
    }


}