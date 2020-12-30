// pages/user/bind.js
let App = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    mobile:'',
    auth_code:''
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

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
  bindInputMobile:function(e){
    this.data.mobile = e.detail;
  },
  bindInputAuthcode:function(e){
    this.data.auth_code = e.detail;
  },
  submitBind: function() {
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
    App._post_form('user.index/bind', {
      mobile: this.data.mobile,
      auth_code: this.data.auth_code,
    }, function(result) {
          console.log('success');
          App.showSuccess(result.msg, function() {
            // 跳转到未付款订单
            wx.navigateTo({url: '../user/index'});
          });


        }, false, function() {
          // complete
          console.log('complete');
          // 解除按钮禁用
          _this.data.disabled = false;
        });
  },
})