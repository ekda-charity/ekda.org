
namespace("ekda.admin").QurbaniAdminModel = function (data) {
    var self = this;
    
    self.details = data.qurbanidetails;
    self.qurbaniSearch = data.qurbani;
    self.purchasedSheep = data.purchasedSheep || 0;
    self.purchasedCows = data.purchasedCows || 0;
    self.purchasedCamels = data.purchasedCamels || 0;
    self.results = ko.observableArray([]);
    self.includevoid = ko.observable(false);
    self.purchasedonly = ko.observable(true);
    self.qurbani = new ekda.index.Qurbani(data.qurbanidetails, true);
    self.QurbaniViewModel = ko.observable(null);

    self.toggleVoid = function(qurbani) {
        var msg = "This will " + (qurbani.isvoid ? "activate" : "void" ) + " the donation. Are you sure?"
        
        bootbox.confirm(msg, function(result) {
            if (result) {
                var url = "/api/QurbaniApi/togglequrbanivoid/" + qurbani.qurbanikey;

                ajaxGet(url, function (response) {
                    if (!response.success) {
                        toastrErrorFromList(response.errors, "Toggle Void Failed");
                    } else {
                        self.searchQurbani();
                        toastrSuccess("Toggle was successful");
                    }
                });        
            }
        });
    };
    
    self.searchQurbani = function () {
        
        var po = self.purchasedonly() ? 1 : 0;
        var iv = self.includevoid() ? 1 : 0;
        var url = "/api/QurbaniApi/searchqurbani/" + po + "/" + iv;
        
        var qsr = document.querySelector("#qurbani-search");
        var lad = Ladda.create(qsr);
        lad.start();

        ajaxGet(url, function (response) {
            lad.stop();
            if (!response.success) {
                toastrErrorFromList(response.errors, "Search Failed");
            } else {
                self.results(response.items);
            }
        });        
    };

    self.editDonation = function (item) {
    };

    $(function () {
        self.qurbani.donatedCallback = self.searchQurbani;
        self.searchQurbani();
    });
    
};