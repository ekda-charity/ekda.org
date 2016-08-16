/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var utils = utils || {};

utils.CookieManager = function () {
    var self = this;
    
    self.expires = 20;
    self.adhashlist = !Cookies.get(utils.CookieKeys.Ad) ? [] : Cookies.get(utils.CookieKeys.Ad).split(",");
    self.respondenthashlist = !Cookies.get(utils.CookieKeys.Respondent) ? [] : Cookies.get(utils.CookieKeys.Respondent).split(",");
    
    self.setAd = function (key, hash) {
        self.adhashlist.push(key + ":" + hash);
        Cookies.set(utils.CookieKeys.Ad, self.adhashlist.join(","), { 
            expires : moment().add(self.expires, 'days').format("YYYY-MM-DD")
        });
    };
    
    self.getAdHash = function (key) {
        var token = ko.utils.arrayFirst(self.adhashlist, function (x) {
            return x.indexOf(key + ":") === 0;
        });
        
        return !token ? null : token.split(":")[1];
    };
    
    self.extendAd = function () {
        Cookies.set(utils.CookieKeys.Ad, self.adhashlist.join(","), { 
            expires : moment().add(self.expires, 'days').format("YYYY-MM-DD")
        });        
    };
    
    self.removeAd = function (key) {
        var tokenIndex = -1;
        
        $.each(self.adhashlist, function (index, x) {
            if (x.indexOf(key + ":") === 0) {
                tokenIndex = index;
                return false;
            }
        });
        
        if (tokenIndex < 0) {
            return;
        }
        
        self.adhashlist.splice(tokenIndex, 1);
        Cookies.set(utils.CookieKeys.Ad, self.adhashlist.join(","), { 
            expires : moment().add(self.expires, 'days').format("YYYY-MM-DD")
        });
    };
    
    self.setRespondent = function (adkey, respondentkey, hash) {
        self.respondenthashlist.push(adkey + ":" + respondentkey + ":" + hash);
        Cookies.set(utils.CookieKeys.Respondent, self.respondenthashlist.join(","), { 
            expires : moment().add(self.expires, 'days').format("YYYY-MM-DD")
        });
    };
    
    self.getRespondentHashByRespondentkey = function (respondentkey) {
        var tokens = ko.utils.arrayFilter(self.respondenthashlist, function (x) {
            return x.indexOf(":" + respondentkey + ":") > 0;
        });
        
        if (tokens.length > 1) {
            throw "Multiple Advertisements for respondentkey: '" + respondentkey + "'";
        } else {
            return tokens.length === 0 ? null : tokens[0].split(":")[2];
        }
    };
    
    self.extendRespondent = function () {
        Cookies.set(utils.CookieKeys.Respondent, self.respondenthashlist.join(","), { 
            expires : moment().add(self.expires, 'days').format("YYYY-MM-DD")
        });        
    };
    
    self.removeRespondent = function (respondentkey) {
        var tokenIndex = -1;
        
        $.each(self.respondenthashlist, function (index, x) {
            if (x.indexOf(":" + respondentkey + ":") > 0) {
                tokenIndex = index;
                return false;
            }
        });
        
        if (tokenIndex < 0) {
            return;
        }        
        
        self.respondenthashlist.splice(tokenIndex, 1);
        Cookies.set(utils.CookieKeys.Respondent, self.respondenthashlist.join(","), { 
            expires : moment().add(self.expires, 'days').format("YYYY-MM-DD")
        });        
    };
    
    self.expireAll = function () {
        Cookies.expire(utils.CookieKeys.Ad);
        Cookies.expire(utils.CookieKeys.Respondent);
    };
};
