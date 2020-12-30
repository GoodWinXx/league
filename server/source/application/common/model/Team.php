<?php


namespace app\common\model;

use think\Request;

class Team extends BaseModel
{
    protected $name = 'team';

    public function getList($map=[])
    {
        $request = Request::instance();
        return $this->where($map)->paginate(15, false, ['query' => $request->request()]);
    }
}