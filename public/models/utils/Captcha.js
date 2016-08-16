/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var utils = utils || {};

utils.Captcha = function () {
    var self = this;
    
    self.data = {
        captchaname: null,
        captchatoken: null,
        captchaword: ko.observable(null),
        captcharesponse: ko.observable(null)
    };
    
    self.isInFocus = ko.observable(false);
    self.isInFocus.subscribe(function (newValue) {
        if (newValue) {
            self.data.captcharesponse(utils.reverse(self.data.captchaword()));
        }
    });
    
    self.validateCaptcha = function (callback) {
        var url = "/api/GeneralApi/validatecaptcha";
        var obj = ko.toJSON(self.data);
        
        ajaxPost(url, obj, function (response) {
            if (!response.success) {
                toastrErrorFromList(response.errors, "Captcha Validation Failed");
            } else if (!response.item) {
                self.resetCaptcha();
                toastrError("Invalid Verification Code. Please Try Again");
            } else {
                self.resetCaptcha();
                callback();
            }
        });
    };

    self.resetCaptcha = function () {
        var url = "/api/GeneralApi/resetcaptcha";
        
        ajaxGet(url, function (response) {
            if (!response.success) {
                toastrErrorFromList(response.errors, "Failed to Reset Verification Code");
            } else {
                self.data.captchaname = response.item.captchaname;
                self.data.captchatoken = response.item.captchatoken;
                self.data.captchaword(response.item.captchaword);
                self.data.captcharesponse(null);
            }
        });
    };
    
    $(function() {
        self.resetCaptcha();
    });

};
