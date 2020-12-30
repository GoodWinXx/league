// pages/category/category.js
let App = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    categories: [],
    goodsWrap: [],
    categorySelected: "",
    goodsToView: "",
    categoryToView: "",
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    this.initData();
  },
  initData() {

    wx.showNavigationBarLoading();
    let _this = this;
    App._get('category/shoplists', {}, function(res) {
      var categories = [];
      if (res.code == 1) {
        var data = res.data.data;
        for (var i = 0; i < data.length; i++) {

          let item = data[i];

          item.scrollId = "s" + item.id;
          categories.push(item);

          if (i == 0) {

            _this.setData({
              categorySelected: item.scrollId,
            })

          }
        }
      }
      _this.setData({
        categories: categories,

      });
      _this.getGoodsList(0);
    });
    
  },
  getGoodsList: function(categoryId, append) {

    let that = this;

      let goodsWrap = [];

      App._get('category/shop', {}, function(res) {
      that.data.categories.forEach((o, index) => {

        let wrap = {};
        wrap.id = o.id;
        wrap.scrollId = "s" + o.id;
        wrap.name = o.name;
        let goods = [];

        wrap.goods = goods;
        res.data.data.forEach((item, i) => {

          if (item.region_id == wrap.id) {

            goods.push(item)
          }
        })
        wrap.goods = goods;
        goodsWrap.push(wrap);
      });



      that.setData({
        goodsWrap: goodsWrap,
      });
    });


      wx.hideNavigationBarLoading();
  },
  toDetailsTap: function(e) {
    wx.navigateTo({
      url: "/pages/store/store?shop_id=" + e.currentTarget.dataset.id
    })
  },
  onCategoryClick: function(e) {

    let id = e.currentTarget.dataset.id;
    this.categoryClick = true;
    this.setData({
      goodsToView: id,
      categorySelected: id,
    })

  },
  scroll: function(e) {

    if (this.categoryClick){
      this.categoryClick = false;
      return;
    }

    let scrollTop = e.detail.scrollTop;

    let that = this;

    let offset = 0;
    let isBreak = false;

    for (let g = 0; g < this.data.goodsWrap.length; g++) {

      let goodWrap = this.data.goodsWrap[g];

      offset += 30;

      if (scrollTop <= offset) {

        if (this.data.categoryToView != goodWrap.scrollId) {
          this.setData({
            categorySelected: goodWrap.scrollId,
            categoryToView: goodWrap.scrollId,
          })
        }

        break;
      }


      for (let i = 0; i < goodWrap.goods.length; i++) {

        offset += 91;

        if (scrollTop <= offset) {

          if (this.data.categoryToView != goodWrap.scrollId) {
            this.setData({
              categorySelected: goodWrap.scrollId,
              categoryToView: goodWrap.scrollId,
            })
          }

          isBreak = true;
          break;
        }
      }

      if (isBreak){
        break;
      }


    }

  
  }
})