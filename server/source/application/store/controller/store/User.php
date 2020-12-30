<?php

namespace app\store\controller\store;

use app\store\controller\Controller;
use app\store\model\StoreUser as StoreUserModel;
use app\store\model\Shop as ShopModel;
use app\store\model\StoreRole as StoreRoleModel;

/**
 * 商户管理员控制器
 * Class StoreUser
 * @package app\store\controller
 */
class User extends Controller
{


    public function index()
    {
        $model = new StoreUserModel;
        $list = $model->getList();
        return $this->fetch('index', compact('list'));
    }


    public function add()
    {
        $model = new StoreUserModel;
        if (!$this->request->isAjax()) {
            $shopModel = new ShopModel;
            $shop_lists = $shopModel->getList();
            $roleModel = new StoreRoleModel;
            $role_lists = $roleModel->getList();
            // echo \think\Db::getLastSql();exit;
            // pre($role_lists);
            return $this->fetch('add',compact('shop_lists','role_lists'));
        }

        // 新增记录
        if ($model->add($this->postData('user'))) {
            return $this->renderSuccess('添加成功', url('store.user/index'));
        }
        $error = $model->getError() ? $model->getError() : '添加失败';
        return $this->renderError($error);
    }


    public function edit($store_user_id)
    {
        // 模板详情
        $model = StoreUserModel::get($store_user_id);
        if (!$this->request->isAjax()) {
            $shopModel = new ShopModel;
            $shop_lists = $shopModel->getList();
            $shop_id_arr = $model->shop_ids ? json_decode($model->shop_ids,true) : [];
            $roleModel = new StoreRoleModel;
            $role_lists = $roleModel->getList();
            $role_id_arr = $model->role_ids ? json_decode($model->role_ids,true) : [];
            return $this->fetch('edit', compact('model','shop_id_arr','shop_lists','role_lists','role_id_arr'));
        }
        // 更新记录
        if ($model->edit($this->postData('user'))) {
            return $this->renderSuccess('更新成功', url('store.user/index'));
        }
        $error = $model->getError() ?: '更新失败';
        return $this->renderError($error);
    }


  
    public function delete($user_id)
    {
        $model = StoreUserModel::get($user_id);
        if (!$model->delete($user_id)) {
            $error = $model->getError() ?: '删除失败';
            return $this->renderError($error);
        }
        return $this->renderSuccess('删除成功');
    }


    /**
     * 更新当前管理员信息
     * @return array|mixed
     * @throws \think\exception\DbException
     */
    public function renew()
    {
        $model = StoreUserModel::detail($this->store['user']['store_user_id']);
        if ($this->request->isAjax()) {
            if ($model->renew($this->postData('user'))) {
                return $this->renderSuccess('更新成功');
            }
            return $this->renderError($model->getError() ?: '更新失败');
        }
        return $this->fetch('renew', compact('model'));
    }
}
