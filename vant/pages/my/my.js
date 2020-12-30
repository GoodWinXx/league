let App = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    active:1,
    userInfo: {}
  },
  onChange(event) {
    if (event.detail == 1) {
      wx.navigateTo({url: '../user/index'});
    } else {
      wx.navigateTo({url: '../shop/shop'});
    }
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

  }
})