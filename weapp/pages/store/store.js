// pages/store/store.js
let App = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    swiperCurrent: 0, //当前banner所在位置
    bannerList: [],
    shopSubList: [],
    store_name:"",
    shop_id:0,
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    let _this = this;
    // 商品id
    _this.data.shop_id = options.shop_id;
    App._get('shop/banners', {shop_id:_this.data.shop_id}, function(res) {
      if (res.code === 1) {
        _this.setData({
          bannerList: res.data.banners
        })
      }
    })
    App._get('shop/index', {shop_id:_this.data.shop_id}, function(res) {
      if (res.code === 1) {
        console.log(res.data.shop);
        _this.setData({
          shopSubList: res.data.shop
        })
      }
    })
    wx.setNavigationBarTitle({
      title: this.data.store_name
    })
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
  swiperchange: function(e) { // banner滚动事件
    this.setData({
      swiperCurrent: e.detail.current
    })
  },
  callPhone(e){
    const tel = e.currentTarget.dataset.tel
    wx.makePhoneCall({
      phoneNumber: tel
    })
  },
  useBuy(e) {
    wx.navigateTo({
        url: '../maidan/index?shop_id=' + e.currentTarget.dataset.id,
      })
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