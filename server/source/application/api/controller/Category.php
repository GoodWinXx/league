<?php

namespace app\api\controller;

use app\api\model\Category as CategoryModel;

use think\Db;

/**
 * 商品分类控制器
 * Class Goods
 * @package app\api\controller
 */
class Category extends Controller
{
    /**
     * 全部分类
     * @return array
     */
    public function lists()
    {
        $list = array_values(CategoryModel::getCacheTree());
        return $this->renderSuccess(compact('list'));
    }


    public function shoplists()
    {
        $data = Db::name('region')->where('id','in',[1170,1171,1172,1173,1174])->select();
        return $this->renderSuccess(compact('data'));
    }


    public function shop()
    {
        $data = Db::name('shop')->where('enabled',1)->select();
        return $this->renderSuccess(compact('data'));
    }
}
