
namespace("ekda.admin").AdminLoginModel = function () {
    var self = this;
    
    self.username = ko.observable();
    self.password = ko.observable();
    
    self.login = function (data, e) {
        
        if (!self.username() || !self.password()) {
            toastrError("Username and Password are both required");
            return;
        }
        
        var ladda = Ladda.create(e.currentTarget);
        ladda.start();
        
        var url = "/api/AdminApi/login";
        var obj = ko.toJSON({
            username: self.username(),
            password: self.password()
        });
        
        ajaxPost(url, obj, function (response) {
            if (!response.success) {
                toastrErrorFromList(response.errors, "Login Failed");
            } else {
                location.href = "/Admin/qurbani/";
            }
            ladda.stop();
        });
        
    };
    
};

