
namespace("ekda.index").NewsModel = function (data) {
    var self = this;
    self.news = data.news;
    
    self.sidebar = ko.utils.arrayMap(data.sidebar, function (x){
        return $.extend(x, { active: ko.observable(false) });
    });
    
    self.currentNews = ko.observableArray(self.news);
    
    self.selectMonth = function (month) {
        ko.utils.arrayForEach(self.sidebar, function (x) {
            x.active(false);
        });
        month.active(true);
        self.currentNews(month.posts);
    };
    
    self.selectAllMonths = function () {
        ko.utils.arrayForEach(self.sidebar, function (x) {
            x.active(false);
        });
        
        self.currentNews(self.news);
    };
    
};