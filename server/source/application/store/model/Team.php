<?php


namespace app\store\model;
use app\common\model\Team as TeamModel;
use think\Session;
use think\Request;
use think\Db;

class Team extends TeamModel
{
    /**
     * 添加新记录
     * @param $data
     * @return false|int
     */
    public function add($data)
    {
        if (isset($data['logo_id'])) {
            $data['logo'] = \app\common\model\UploadFile::getFileUrl($data['logo_id']);
        }

        if (!empty($data['images'])) {
            $images = [];
            foreach ($data['images'] as $key=>$val) {
                $images[$key]['image_id'] = $val;
                $images[$key]['img_url'] = \app\common\model\UploadFile::getFileUrl($val);
            }
        }

        $data['create_time'] = time();
        $data['wxapp_id'] = self::$wxapp_id;
        $res = $this->allowField(true)->save($data);
//        if (!empty($data['images'])) {
//
//            foreach ($data['images'] as $key=>$val) {
//                $images = [];
//                $images['shop_id'] = $this->id;
//                $images['image_id'] = $val;
//                $images['img_url'] = \app\common\model\UploadFile::getFileUrl($val);
//                Db::name('shop_banners')->insert($images);
//            }
//
//        }

        return $res;
    }

    public function edit($data)
    {
        $data['logo'] = \app\common\model\UploadFile::getFileUrl($data['logo_id']);
        if (!empty($data['images'])) {
            Db::name('shop_banners')->where('shop_id',$this->id)->delete();
            foreach ($data['images'] as $key=>$val) {
                $images = [];
                $images['shop_id'] = $this->id;
                $images['image_id'] = $val;
                $images['img_url'] = \app\common\model\UploadFile::getFileUrl($val);
                Db::name('shop_banners')->insert($images);
            }

        }
        return $this->allowField(true)->save($data);
    }
}