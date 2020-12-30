// pages/checkout/checkout.js
let App = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    shop_id:0,
    shop:{},
    points:0
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    let _this = this;
     _this.data.shop_id = options.shop_id;
     _this.getShopDetail();
  },

  getShopDetail() {
    let _this = this;
    App._get('index/shop', {
      shop_id: _this.data.shop_id
    }, function(result) {
      if (result.code == -99) {
        wx.navigateTo({url: '../user/bind'});
      }
      let data = result.data;
      _this.setData(data);
    });
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  },

  bindInputPoints:function(e){
    this.data.points = e.detail;
  },
    /**
   * 订单提交
   */
  submitOrder: function() {
    let _this = this,
      options = _this.data.options;

    if (_this.data.disabled) {
      return false;
    }

    if (_this.data.hasError) {
      App.showError(_this.data.error);
      return false;
    }

    // 按钮禁用, 防止二次提交
    _this.data.disabled = true;

    // 显示loading
    wx.showLoading({
      title: '正在处理...'
    });

    // 创建订单-立即购买
    App._post_form('order/shopOrder', {
      shop_id: this.data.shop_id,
      points: this.data.points,
    }, function(result) {
      //跳转回首页
      if (result.code == 1) {
        App.showSuccess(result.data, function() {
          wx.navigateTo({url: '../shop/shop'});
        });
      } else {
        App.showError(result.data, function() {
        });
      }
      console.log('success');
    }, function(result) {
      // fail
      console.log('fail');
    }, function() {
      // complete
      console.log('complete');
      // 解除按钮禁用
      _this.data.disabled = false;
    });
  },
})