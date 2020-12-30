let App = getApp();

Page({
  data: {
    balance: 0.00,
    freeze: 0,
    score: 0,
    score_sign_continuous: 0,
    orderList:[],
  },
  onLoad() {

  },
  onShow() {
    this.orderList()
  },  
  
  orderList(){
    let _this = this;
    App._get('order/shopOrderLists', {}, function(res) {
      if (res.code === 1) {
        _this.setData({
          orderList: res.data.orders,
        });
      }
    })
    
  }
})