<?php

namespace app\store\controller;

use app\store\model\User as UserModel;
use think\Cache;
use app\store\model\ShopOrder as ShopOrderModel;
use think\Db;

/**
 * 用户管理
 * Class User
 * @package app\store\controller
 */
class User extends Controller
{
    /**
     * 用户列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $request_data = $this->request->param();
        $map = [];
        if (!empty($request_data['search'])) {
            $name = trim($request_data['search']);
            $user_ids= \think\Db::name('user')->where('real_name|mobile','like','%'.$name.'%')->column('user_id');
            if ($user_ids) {
                $map['user_id'] = ['in',$user_ids];
            }
        }
        $model = new UserModel;
        $list = $model->getList($map);
        return $this->fetch('index', compact('list','request_data'));
    }


    public function add()
    {
        $model = new UserModel;
        if (!$this->request->isAjax()) {
            
            return $this->fetch('add');
        }

        // 新增记录
        if ($model->add($this->postData('user'))) {
            return $this->renderSuccess('添加成功', url('user/index'));
        }
        $error = $model->getError() ? $model->getError() : '添加失败';
        return $this->renderError($error);
    }


    public function edit($user_id)
    {
        // 模板详情
        $model = UserModel::get($user_id);
        if (!$this->request->isAjax()) {
            return $this->fetch('edit', compact('model'));
        }
        // 更新记录
        if ($model->edit($this->postData('user'))) {
            return $this->renderSuccess('更新成功', url('user/index'));
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
    public function delete($user_id)
    {
        $model = UserModel::get($user_id);
        if (!$model->remove($user_id)) {
            $error = $model->getError() ?: '删除失败';
            return $this->renderError($error);
        }
        return $this->renderSuccess('删除成功');
    }


    public function recharge()
    {
        if ($this->request->isAjax()) {
            $user_id = $this->postData('user_id');
            $model = UserModel::get($user_id);
            if ($model->recharge($this->postData('recharge'))) {
                return $this->renderSuccess('充值成功', url('user/index'));
            }
            $error = $model->getError() ? $model->getError() : '充值失败';
            return $this->renderError($error);
        }
        return $this->renderSuccess('充值失败');
    }


    public function orders($user_id)
    {
        $map = $search = [];
        $search_param = [
            'start_time' => '',
            'end_time' => '',
            'search' => ''
        ];
        $search_params = $this->request->param();
        $search_param = array_merge($search_param,$search_params);
        $data_type = !empty($search_param['dataType']) ? $search_param['dataType'] : '';
        if (!empty($search_param['search'])) {
            $map['order_sn'] = ['like','%'.trim($search_param['search']).'%'];
        }
        if (!empty($search_param['start_time'])) {
            
            $map['create_time'] = ['gt',strtotime($search_param['start_time'])];
        }
        if (!empty($search_param['end_time'])) {
            
            $map['create_time'] = ['lt',strtotime($search_param['end_time'])+86400];
        }
        $model = new ShopOrderModel;
        $map['user_id'] = $user_id;

        $list = $model->getLists($map);
        
        return $this->fetch('orders',compact('list','user_id','search','search_param','data_type'));
    }

    public function sms($user_id)
    {
        if (!$user_id) {
            return $this->renderError('用户信息不存在');
        }
        $user = Db::name('user')->where('user_id',$user_id)->find();
        if (empty($user['mobile'])) {
            return $this->renderError('用户手机号不存在');
        }
        if (empty($user['auth_code'])) {
            $model = new UserModel;
            $user['auth_code'] = $model->randomStr(4);
            Db::name('user')->where('user_id',$user_id)->update(['auth_code'=>$user['auth_code']]);
        }
        $smsApi = new \app\common\library\sms\SmsBao;
        $result = $smsApi->sendSms($user['mobile'],$user['auth_code'],$user['current_points']);
        if ($result['code'] == 0) {
            Db::name('user')->where('user_id',$user_id)->update(['is_send'=>1]);
            return $this->renderError('短信发送成功');
        }
        return $this->renderError($result['msg']);
    }

}
