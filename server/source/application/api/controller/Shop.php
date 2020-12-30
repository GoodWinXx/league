<?php 
namespace app\api\controller;

use think\Db;

class Shop extends Controller
{

    public function recomend()
    {
        $shops = Db::name('shop')->where('enabled',1)->order('sort asc')->limit(8)->select();
        return $this->renderSuccess(compact('shops'));
    }


    public function search($keyword)
    {
        $shops = Db::name('shop')->where('enabled',1)->where('shop_name','like','%'.trim($keyword).'%')->order('sort asc')->select();
        return $this->renderSuccess(compact('shops'));
    }


    public function index($shop_id)
    {
        if (empty($shop_id)) {
            return $this->renderError('门店信息获取失败');
        }
        $shop = Db::name('shop')->where('id',$shop_id)->find();
        // $shop = [
        //     "cityId"=> "330100000000",
        //     "dateAdd"=> "2019-03-25 13:30:51",
        //     "dateUpdate"=> "2019-03-25 14:>17:>37",
        //     "districtId"=> "330106000000",
        //     "latitude"=> 30.136940,
        //     "linkPhone"=> "0571-888666555",
        //     "longitude"=> 120.219925,
        //     "numberGoodReputation"=> 0,
        //     "numberOrder"=> 0,
        //     "openingHours"=> "9:00-22:00",
        //     "paixu"=> 0,
        //     "provinceId"=> "330000000000",
        //     "status"=> 0,
        //     "statusStr"=> "正常",
        //     "userId"=> 13886
        // ];

        return $this->renderSuccess(compact('shop'));
    }


    public function banners($shop_id)
    {
        $banners = [];
        if (!empty($shop_id)) {
            $data = Db::name('shop_banners')->where('shop_id',$shop_id)->select();
            foreach ($data as $k=>$v) {
                $banners[$k]['businessId'] = $shop_id;
                $banners[$k]['picUrl'] = $v['img_url'];
            }
        }
        
        return $this->renderSuccess(compact('banners'));
    }
}