let App = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    active:1,
    userInfo: {},
    orderCount: {},
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {

  },
  onChange(event) {
    if (event.detail == 1) {
      wx.navigateTo({url: '../user/index'});
    } else {
      wx.navigateTo({url: '../shop/shop'});
    }
  },
  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function() {
    // 获取当前用户信息
    this.getUserDetail();
  },

  /**
   * 获取当前用户信息
   */
  getUserDetail: function() {
    let _this = this;
    App._get('user.index/detail', {}, function(result) {
      _this.setData(result.data);
    });
  },

  /**
   * 订单导航跳转
   */
  onTargetOrder(e) {
    // 记录formid
    // App.saveFormId(e.detail.formId);
    let urls = {
      all: '/pages/order/index?type=all',
      payment: '/pages/order/index?type=payment',
      received: '/pages/order/index?type=received',
      account:'/pages/my/my',
      bind:'/pages/user/bind',
      order:'/pages/order/order',
      shop_order:'/pages/shoporder/index',
    };
    // 转跳指定的页面
    wx.navigateTo({
      url: urls[e.currentTarget.dataset.type]
    })
  },

  /**
   * 菜单列表导航跳转
   */
  onTargetMenus(e) {
    // 记录formId
    // App.saveFormId(e.detail.formId);
    wx.navigateTo({
      url: '/' + e.currentTarget.dataset.url
    })
  },

})