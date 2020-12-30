<?php

namespace app\common\model;

class ShopOrder extends BaseModel
{
    protected $name = 'shop_order';
    protected $updateTime = false;


    public function getShopNameAttr($value,$data)
    {
        $shop = Shop::get($data['shop_id']);
        return $shop ? $shop->shop_name : '';
    }


    public function getNickNameAttr($value,$data)
    {
        $shop = User::get($data['user_id']);
        return $shop ? $shop->nickName : '';
    }

    public function getRealNameAttr($value,$data)
    {
        $shop = User::get($data['user_id']);
        return $shop ? $shop->real_name : '';
    }
}
