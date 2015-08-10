
namespace("ekda.index").IndexModel = function (data) {
    var self = this;
    self.home = ko.observableArray(data.home);
    self.news = ko.observableArray(data.news);
};