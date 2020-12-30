<?php


namespace app\store\controller;

use app\store\model\Team as TeamModel;
use think\Db;

class Team extends Controller
{
    public function index()
    {
        $model = new TeamModel;
        $list = $model->getList([]);
        return $this->fetch('index', compact('list'));
//        $list = Db::name('team')->where('status=1')->select()->toArray();
//        if ($list['win'] || $list['lose'] == 0){
//            return $this->fetch('index', compact('list'));
//        }else{
//            foreach ($list as $key=>$value){
//                $list[$key]['rate'] = round($value['win']/($value['win']+$value['lose']),2)*100;
//            }
//            return $this->fetch('index', compact('list'));
//        }
    }

    public function add()
    {
        $model = new TeamModel;
        if (!$this->request->isAjax()) {
            $region = Db::name('region')->where('id','in',[1170,1171,1172,1173,1174])->select();
            return $this->fetch('add',compact('region'));
        }
        // 新增记录
        if ($model->add($this->postData('team'))) {
            return $this->renderSuccess('添加成功', url('team/index'));
        }
        $error = $model->getError() ? $model->getError() : '添加失败';
        return $this->renderError($error);
    }

    public function edit($team_id)
    {
        // 模板详情
        $model = TeamModel::get($team_id);
        if (!$this->request->isAjax()) {
            return $this->fetch('edit', compact('model'));
        }
        // 更新记录
        if ($model->edit($this->postData('team'))) {
            return $this->renderSuccess('更新成功', url('team/index'));
        }
        $error = $model->getError() ?: '更新失败';
        return $this->renderError($error);
    }

    public function delete($team_id)
    {
        $model = TeamModel::get($team_id);
        if (!$model->delete($team_id)) {
            $error = $model->getError() ?: '删除失败';
            return $this->renderError($error);
        }
        return $this->renderSuccess('删除成功');
    }
}