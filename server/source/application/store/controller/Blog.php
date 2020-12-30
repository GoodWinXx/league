<?php
/**
 * Created by PhpStorm.
 * User: chenweihua
 * Date: 2020/1/31
 * Time: 4:10 PM
 */

namespace app\store\controller;

use app\store\model\Shop as ShopModel;
use think\Db;

class Blog extends Controller
{
    public function index()
    {
        $model = new ShopModel();
        $list = $model->getList([]);
        return $this->fetch('index',compact('list'));
    }

    public function add()
    {
        $model = new ShopModel();
        if (!$this->request->isAjax()){
            $region = Db::name('region')->where('id','in',[1170,1171,1172,1173,1174])->select();
//            dump(compact('region'));
            return $this->fetch('add',compact('region'));
        }
        // 新增记录
        if ($model->add($this->postData('shop'))) {
            return $this->renderSuccess('添加成功', url('shop/index'));
        }
        $error = $model->getError() ? $model->getError() : '添加失败';
        return $this->renderError($error);
    }

    public function edit()
    {
        return $this->fetch('edit');
    }
}