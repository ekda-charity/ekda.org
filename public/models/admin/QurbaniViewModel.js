/* global namespace, ekda, ko, Ladda */

namespace("ekda.admin").QurbaniViewModel = function (qurbani, details, callback) {
    var self = this;
    
    self.numbers = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];
    self.qurbani = new ekda.index.QurbaniDonation(qurbani, details);
    self.callback = callback;

    self.update = function (data, e) {
        
        var url = "/api/QurbaniApi/updatequrbani";
        var obj = ko.toJSON(self.qurbani);
        
        var lad = Ladda.create(e.currentTarget);
        lad.start();

        ajaxPost(url, obj, function (response) {
            lad.stop(); 
            if (!response.success) {
                toastrErrorFromList(response.errors, "Update Failed");
            } else {
                if (self.callback) {
                    self.callback(response.writtenKey);
                }
            }
        });
    };
};