
namespace("ekda.admin").QurbaniAdminModel = function (data) {
    var self = this;
    
    self.details = data.qurbanidetails;
    self.qurbaniSearch = data.qurbani;
    self.purchasedSheep = data.purchasedSheep || 0;
    self.purchasedCows = data.purchasedCows || 0;
    self.purchasedCamels = data.purchasedCamels || 0;
    self.results = ko.observableArray([]);
    self.qurbani = new ekda.index.Qurbani(data.qurbanidetails, true);
    

    $(function () {
        if (!self.qurbaniSearch.success) {
            toastrErrorFromList(self.qurbaniSearch.errors, "Search Failed");
        } else {
            self.results(self.qurbaniSearch.items);
        }
    });
    
};