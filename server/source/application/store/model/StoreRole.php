<?php

namespace app\store\model;

use app\common\model\StoreRole as StoreRoleModel;
use think\Session;
use think\Request;

class StoreRole extends StoreRoleModel
{
    public function getList($map=[])
    {   
        $request = Request::instance();
        return $this->where($map)->order(['id' => 'desc'])
            ->paginate(15, false, ['query' => $request->request()]);
    }
}