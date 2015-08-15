var app = app || {};
app.index = app.index || {};
app.index.Models = app.index.Models || {};

namespace("ekda.index").Qurbani = function (data, confirmDonation) {
    var self = this;
    
    self.numbers = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];
    self.sheepCost = data.sheepcost;
    self.cowCost = data.cowcost;
    self.camelCost = data.camelcost;
    self.qurbaniseason = !data.qurbaniseason ? false : true;
    self.confirmDonation = !confirmDonation ? false : true;
    
    self.sheep = ko.observable(1);
    self.cows = ko.observable(0);
    self.camels = ko.observable(0);
    
    self.addInstructions = ko.observable(false);
    self.fullname = ko.observable();
    self.email = ko.observable();
    self.mobile = ko.observable();
    self.instructions = ko.observable();

    self.sheepTotal = ko.computed(function () {
        return self.sheep() * self.sheepCost;
    });
    
    self.cowsTotal = ko.computed(function () {
        return self.cows() * self.cowCost;
    });
    
    self.camelsTotal = ko.computed(function () {
        return self.camels() * self.camelCost;
    });
    
    self.total = ko.computed(function () {
        return self.sheepTotal() + self.cowsTotal() + self.camelsTotal();
    });
    
    self.donate = function (data, e) {
        
        var errors = [];
        
        if (self.total() <= 0) {
            errors.push("At least one animal is required");
        }
        
        if (self.addInstructions()) {
            if (!utils.validEmail(self.email())) {
                errors.push("Email is required")
            }
            
            if (!self.fullname()) {
                errors.push("Fullname is required")
            }
            
            if (!self.instructions()) {
                errors.push("Names to mention is required")
            }
        }

        if (errors.length > 0) {
            toastrErrorFromList(errors, "Validation Errors");
            return;
        } else {
            var url = "/api/QurbaniApi/" + (self.confirmDonation ? "checkstockanddonate" : "checkstockandinitiatedonation");
            var obj = ko.toJSON({
                sheep: self.sheep(),
                cows: self.cows(),
                camels: self.camels(),
                total: self.total(),
                fullname: self.fullname(),
                email: self.email(),
                mobile: self.mobile(),
                instructions: self.instructions()
            });
            
            var lad = Ladda.create(e.currentTarget);
            lad.start();
        
            ajaxPost(url, obj, function (response) {
                lad.stop();
                if (!response.success) {
                    toastrErrorFromList(response.errors, "Validation Failed");
                } else if (self.confirmDonation) {
                    toastrSuccess("Donation was successfull");
                } else {
                    document.location.href = response.item;
                }
            });
        }
    };
};

