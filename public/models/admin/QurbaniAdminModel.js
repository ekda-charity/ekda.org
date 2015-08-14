
namespace("ekda.admin").QurbaniAdminModel = function (data) {
    var self = this;
    
    self.qurbaniSearch = data.qurbani;
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