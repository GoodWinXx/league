let App = getApp();
Page({
  data: {
    totalScoreToPay: 0,
    shop: [],
    amount:0,
    shop_id:0,
  },
  onShow: function() {
    var that = this;
    var shopList = [];
  },

  onLoad: function(e) {
    let _this = this;
    this.data.shop_id = e.shop_id;
    App._get('shop/index', {shop_id:_this.data.shop_id}, function(res) {
      if (res.code === 1) {
        _this.setData({
          shop: res.data.shop
        })
      }
    })
  },
  getAmountContent: function (e) {
    this.data.amount = e.detail.value;
  },
  toPayTap: function (e) {
    const that = this;
    let money = that.data.amount;
    
    let _msg = '订单金额: ' + money + ' 元'
    wx.showModal({
      title: '请确认支付',
      content: _msg,
      confirmText: "确认支付",
      cancelText: "取消支付",
      success: function (res) {
        if (res.confirm) {
          App._post_form('order/shopOrder', {
            shop_id: that.data.shop_id,
            points: that.data.amount,
          }, function(result) {
            //跳转回首页
            if (result.code == 1) {
              wx.showModal({
                title: '支付成功',
                content: '本次消费：'+result.data.amount+'元，剩余余额'+result.data.current_points+'元',
                confirmText: "查看订单",
                cancelText: "去首页",
                success (res) {
                  if (res.confirm) {
                    wx.navigateTo({
                      url:'../shoporder/index'
                    });
                  } else if (res.cancel) {
                    wx.switchTab({
                      url:'/pages/home/home'
                    });
                  }
                }
              })

              
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
            that.data.disabled = false;
          });
        } else {
          console.log('用户点击取消支付')
        }
      }
    });
      
    }

})