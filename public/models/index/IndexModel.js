
/* global ekda, namespace, ko, bootbox, utils, Ladda */

namespace("ekda.index").IndexModel = function (data) {
    var self = this;
    
    self.posts = data.posts;
    self.qurbani = new ekda.index.QurbaniDonation(null, data.qurbanidetails);
    self.disableinstructionsdate = data.qurbanidetails.disableinstructionsdate;
    self.closingdate = data.qurbanidetails.closingdate;
    self.qurbaniseason = !data.qurbanidetails.qurbaniseason ? false : true;
    self.numbers = _.range(51);
    
    self.disableInstructions = ko.computed(function () {
        return moment() >= moment(self.disableinstructionsdate);
    });
    
    self.closed = ko.computed(function () {
        return moment() >= moment(self.closingdate);
    });
    
    self.donate = function (data, e) {
        
        var errors = [];
        
        if (self.qurbani.total() <= 0) {
            errors.push("At least one animal is required");
        }

        if (errors.length > 0) {
            toastrErrorFromList(errors, "Validation Errors");
            return;
        } else {
            
            var message = '<div class="row">  ' +
                '<div class="col-md-12"> ' +
                    '<form class="form-horizontal"> ' +
                        '<div class="form-group"> ' +
                            '<div class="col-md-12"> ' +
                                (self.disableInstructions() ? '' : '<textarea type="text" id="qurbaniName" name="name" class="form-control input-md" rows="3" placeholder="Names (Optional)"></textarea>') +
                                '<input id="qurbaniEmail" name="email" type="text" placeholder="Email (To alert you after Qurbani)" class="form-control input-md top5"> ' +
                            '</div> ' +
                        '</div> ' +
                    '</form>' +
                '</div> ' +
            '</div> ';

            bootbox.dialog({
                title: (self.disableInstructions() ? "Contact Details" : "Please specify the name(s) to be mentioned during the Qurbani"),
                message: message,
                buttons: {
                    cancel: {
                        label: "Cancel",
                        className: "btn-default",
                        callback: function () {
                        }
                    },
                    success: {
                        label: "Continue",
                        className: "btn-primary",
                        callback: function () {
                            var name = $('#qurbaniName').val();
                            var email = $('#qurbaniEmail').val();
                            
                            if (email && email.trim().length > 0) {
                                email = email.trim();
                                if (!utils.validEmail(email)) {
                                    toastrError("A valid email is required");
                                    return false;
                                }
                            }
                            
                            self.qurbani.instructions(name || null);
                            self.qurbani.email(email || null);
                            
                            var url = "/api/QurbaniApi/checkstockandinitiatedonation";
                            var obj = ko.toJSON(self.qurbani);

                            var lad = Ladda.create(e.currentTarget);
                            lad.start();

                            ajaxPost(url, obj, function (response) {
                                lad.stop();
                                if (!response.success) {
                                    toastrErrorFromList(response.errors, "Validation Failed");
                                } else {
                                    document.location.href = response.item;
                                }
                            });        
                        }
                    }
                }
            });
        }
    };
};