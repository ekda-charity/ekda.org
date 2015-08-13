
namespace("ekda.index").IndexModel = function (data) {
    var self = this;
    
    self.posts = data.posts;
    self.qurbani = new ekda.index.Qurbani(data.qurbanidetails);
    
};