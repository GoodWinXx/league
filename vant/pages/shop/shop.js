// pages/shop/shop.js
let App = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    active: 0,
    // banner轮播组件属性
    indicatorDots: true, // 是否显示面板指示点 
    autoplay: true, // 是否自动切换
    interval: 3000, // 自动切换时间间隔
    duration: 800, // 滑动动画时长
    imgHeights: {}, // 图片的高度
    imgCurrent: {}, // 当前banne所在滑块指针
    // 页面元素
    items: {},
    newest: {},
    best: {},
    shops:{},
    scrollTop: 0,
  },

  onChange(event) {
    if (event.detail == 1) {
      wx.navigateTo({url: '../user/index'});
    } else {
      wx.navigateTo({url: '../index/index'});
    }
  },
  onLoad: function() {
    // 设置页面标题
    App.setTitle();
    // 设置navbar标题、颜色
    App.setNavigationBar();
    // 获取首页数据
    this.getIndexData();
  },

  /**
   * 获取首页数据
   */
  getIndexData: function() {
    let _this = this;
    App._get('index/page', {}, function(result) {
      _this.setData(result.data);
    });
  },

  /**
   * 计算图片高度
   */
  imagesHeight: function(e) {
    let imgId = e.target.dataset.id,
      itemKey = e.target.dataset.itemKey,
      ratio = e.detail.width / e.detail.height, // 宽高比
      viewHeight = 750 / ratio, // 计算的高度值
      imgHeights = this.data.imgHeights;

    // 把每一张图片的对应的高度记录到数组里
    if (typeof imgHeights[itemKey] === 'undefined') {
      imgHeights[itemKey] = {};
    }
    imgHeights[itemKey][imgId] = viewHeight;
    // 第一种方式
    let imgCurrent = this.data.imgCurrent;
    if (typeof imgCurrent[itemKey] === 'undefined') {
      imgCurrent[itemKey] = Object.keys(this.data.items[itemKey].data)[0];
    }
    this.setData({
      imgHeights,
      imgCurrent
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

  }
})