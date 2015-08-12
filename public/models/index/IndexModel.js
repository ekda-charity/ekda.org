
namespace("ekda.index").IndexModel = function (data) {
    var self = this;
    
    self.posts = data.posts;
    self.addSpecialInstructions = ko.observable(false);
    
};