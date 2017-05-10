//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    motto: 'Hello World',
    userInfo: {},
    list:[]
  },
  //事件处理函数
  bindViewTap: function() {
    wx.navigateTo({
      url: '../logs/logs'
    })
  },
  onLoad: function () {
    console.log('onLoad')
    var that = this;
    wx.setNavigationBarTitle({
       title: '完美线路'
    })
    //调用应用实例的方法获取全局数据
    app.getUserInfo(function(userInfo){
      console.log(userInfo);
      //更新数据
      that.setData({
        userInfo:userInfo
      })
      console.log('1');
      // 获取列表数据
      if (wx.showLoading) {
        wx.showLoading({title:'正在加载'});
      }
      wx.request({
        url: 'https://wx.caroltc.win/index.php',
        data: {},
        header: {
          'content-type': 'application/json'
        },
        success: function(res) {
          console.log(res);
          that.setData({
            list:res.data.result
          })
        },
        fail: function(res)
        {
          console.log(res);
          wx.showToast({
            title: '请求失败'
          })
        },
        complete: function() {
           if (wx.showLoading) {
               wx.hideLoading();
           }
        }
     })
    })
  }
})
