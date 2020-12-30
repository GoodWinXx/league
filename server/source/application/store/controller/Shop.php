<?php

namespace app\store\controller;

use app\store\model\Shop as ShopModel;
use think\Cache;
use think\Db;
/**
 * 门店管理
 * Class User
 * @package app\store\controller
 */
class Shop extends Controller
{
   
    public function index()
    {
        $model = new ShopModel;
        $list = $model->getList([]);
        return $this->fetch('index', compact('list'));
    }


    public function add()
    {
        $model = new ShopModel;
        if (!$this->request->isAjax()) {
            $region = Db::name('region')->where('id','in',[1170,1171,1172,1173,1174])->select();
            return $this->fetch('add',compact('region'));
        }
        // 新增记录
        if ($model->add($this->postData('shop'))) {
            return $this->renderSuccess('添加成功', url('shop/index'));
        }
        $error = $model->getError() ? $model->getError() : '添加失败';
        return $this->renderError($error);
    }


    public function edit($shop_id)
    {
        // 模板详情
        $model = ShopModel::get($shop_id);
        if (!$this->request->isAjax()) {
            $region = Db::name('region')->where('id','in',[1170,1171,1172,1173,1174])->select();
            $banners = Db::name('shop_banners')->where('shop_id',$shop_id)->select();
            return $this->fetch('edit', compact('model','region','banners'));
        }
        // 更新记录
        if ($model->edit($this->postData('shop'))) {
            return $this->renderSuccess('更新成功', url('shop/index'));
        }
        $error = $model->getError() ?: '更新失败';
        return $this->renderError($error);
    }


    /**
     * 删除商品分类
     * @param $category_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function delete($shop_id)
    {
        $model = ShopModel::get($shop_id);
        if (!$model->delete($shop_id)) {
            $error = $model->getError() ?: '删除失败';
            return $this->renderError($error);
        }
        return $this->renderSuccess('删除成功');
    }

}
