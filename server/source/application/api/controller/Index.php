<?php

namespace app\api\controller;

use app\api\model\WxappPage;
use app\api\model\Goods as GoodsModel;
use think\Db;
/**
 * 首页控制器
 * Class IndexModel
 * @package app\api\controller
 */
class Index extends Controller
{
    /**
     * 首页diy数据
     * @return array
     * @throws \think\exception\DbException
     */
    public function page()
    {
        // 页面元素
        $wxappPage = WxappPage::detail();
        $items = $wxappPage['page_data']['array']['items'];
        // 新品推荐
        $model = new GoodsModel;
        $newest = $model->getNewList();
        // 猜您喜欢
        $best = $model->getBestList();
        $shops = Db::name('shop')->where(['enabled'=>1])->select();
        return $this->renderSuccess(compact('items', 'newest', 'best','shops'));
    }


    public function shop($shop_id)
    {
        $userInfo = $this->getUser();
        if ($userInfo['is_bind'] == 0) {
            return $this->renderJson(-99,'请先绑定帐号');
        }
        if (!$shop_id) {
            return $this->renderError('很抱歉，门店信息不存在');
        }
        $shop = Db::name('shop')->where(['enabled'=>1,'id'=>$shop_id])->find();
        return $this->renderSuccess(compact('shop'));
    }

}
