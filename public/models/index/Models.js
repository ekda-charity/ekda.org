var app = app || {};
app.index = app.index || {};
app.index.Models = app.index.Models || {};

namespace("ekda.index").QurbaniDonation = function (data, details) {
    var self = this;
    
    self.details = ko.observable(details);
    self.qurbanikey = !data ? null : data.qurbanikey;
    self.qurbanimonth = !data ? null : data.qurbanimonth;
    self.sheep = ko.observable(!data ? 1 : data.sheep);
    self.cows = ko.observable(!data ? 0 : data.cows);
    self.camels = ko.observable(!data ? 0 : data.camels);
    
    self.fullname = ko.observable(!data ? null : data.fullname);
    self.email = ko.observable(!data ? null : data.email);
    self.mobile = ko.observable(!data ? null : data.mobile);
    self.instructions = ko.observable(!data ? null : data.instructions);
    
    self.donationid = !data ? null : data.donationid;
    self.isvoid = ko.observable(!data ? null : data.isvoid);
    self.iscomplete = !data ? null : data.iscomplete;
    self.createddate = !data ? null : data.createddate;
    
    self.sheepCost = ko.computed(function () {
        return !self.details() ? 0 : self.details().sheepcost;
    });
    
    self.cowCost = ko.computed(function () {
        return !self.details() ? 0 : self.details().cowcost;
    });
    
    self.camelCost = ko.computed(function () {
        return !self.details() ? 0 : self.details().camelcost;
    });
    
    self.sheepTotal = ko.computed(function () {
        return self.sheep() * self.sheepCost();
    });
    
    self.cowsTotal = ko.computed(function () {
        return self.cows() * self.cowCost();
    });
    
    self.camelsTotal = ko.computed(function () {
        return self.camels() * self.camelCost();
    });
    
    self.total = ko.computed(function () {
        return self.sheepTotal() + self.cowsTotal() + self.camelsTotal();
    });
};
