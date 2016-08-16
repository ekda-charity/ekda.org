/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var utils = utils || {};

utils.CustomDate = function (date) {
    return moment(date).format('YYYY-MM-DD[T]HH:mm:ssZZ');
}

utils.validEmail = function (email) {
    if (!email) {
        return false;
    } else {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
};

utils.reverse = function (s) {
    if (!s) {
        return s;
    } else {
        return s.toString().split('').reverse().join('');
    }
};

utils.PrayerTime = function (datetime) {
    if (!datetime) {
        return null;
    } else {
        return date
    }
};