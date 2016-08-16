
namespace("ekda.index").PostsModel = function (data) {
    var self = this;
    
    self.posts = data.posts;
    
    self.sidebar = ko.utils.arrayMap(data.sidebar, function (x){
        return $.extend(x, { active: ko.observable(false) });
    });
    
    self.currentPosts = ko.observableArray(self.posts);
    
    self.selectPost = function (post) {
        ko.utils.arrayForEach(self.sidebar, function (x) {
            x.active(false);
        });
        post.active(true);
        self.currentPosts([post]);
    };
    
    self.selectAllPosts = function () {
        ko.utils.arrayForEach(self.sidebar, function (x) {
            x.active(false);
        });
        
        self.currentPosts(self.sidebar);
    };
};