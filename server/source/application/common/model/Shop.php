<?php

namespace app\common\model;

use think\Request;

class Shop extends BaseModel
{
    protected $name = 'shop';

    public function image()
    {
        return $this->hasOne('uploadFile', 'file_id', 'logo_id');
    }


    public function getEnabledTextAttr($value,$data)
    {
        return $this->enable_text[$data['enabled']] ?? '';
    }

    public function getList($map=[])
    {
        $request = Request::instance();
        return $this->where($map)->order(['sort' => 'asc'])
            ->paginate(15, false, ['query' => $request->request()]);
    }
}
