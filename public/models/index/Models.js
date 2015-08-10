var app = app || {};
app.index = app.index || {};
app.index.Models = app.index.Models || {};

app.index.Models.Ad = function (data) {
    var self = this;
    self.adcategorykey = ko.observable(!data ? null : data.adcategorykey);
    self.adkey = !data ? null : data.adkey;
    self.createddate = !data || !data.createddate ? utils.CustomDate() : data.createddate;
    self.email = ko.observable(!data ? null : data.email);
    self.exposemycontacts = ko.observable(!data || data.exposemycontacts === undefined ? false : data.exposemycontacts);
    self.isactive = ko.observable(!data ? true : data.isactive);
    self.location = ko.observable(!data ? null : data.location);
    self.message = ko.observable(!data ? null : data.message);
    self.password = ko.observable(!data ? null : data.password);
    self.phone = ko.observable(!data ? null : data.phone);
    self.price = ko.observable(!data ? null : data.price);
    self.title = ko.observable(!data ? null : data.title);
    self.updateddate = !data || !data.updateddate ? utils.CustomDate() : data.updateddate;
    self.turnonalerts = ko.observable(!data || data.turnonalerts === undefined ? true : data.turnonalerts);

    self.summary = ko.computed(function () {
        return self.title() + ", " + self.location() + ", " + self.price();
    });
};

app.index.Models.Respondent = function (data) {
    var self = this;
    self.respondentkey = !data ? null : data.respondentkey;
    self.adkey = !data ? null : data.adkey;
    self.createddate = !data || !data.createddate ? utils.CustomDate() : data.createddate;
    self.email = ko.observable(!data ? null : data.email);
    self.password = ko.observable(!data ? null : data.password);
    self.publicname = ko.observable(!data ? null : data.publicname);
    self.turnonalerts = ko.observable(!data || data.turnonalerts === undefined ? true : data.turnonalerts);
};

app.index.Models.YoutubePost = function (data) {
    var self = this;
    self.snippet  = !data ? null : data.snippet;
    self.monthnum = !data ? null : moment(self.snippet.publishedAt).format("YYYYMM");
    
    self.trimmedTitle = ko.computed(function () {
        if (!self.snippet || !self.snippet.title) {
            return null;
        } else {
            var list = self.snippet.title.split("|");
            if (list.length === 3) {
                return list[1].trim();
            } else {
                return self.snippet.title.replace("Spital Khutba |", "").replace("Spital Khutba -", "").trim();
            }
        }
    });
    
    self.key = ko.computed(function () {
        if (!self.snippet || !self.snippet.resourceId || !self.snippet.resourceId.videoId) {
            return null;
        } else {
            return self.snippet.resourceId.videoId;
        }
    });
};

app.index.Models.Message = function (data) {
    var self = this;
    self.messagekey = !data ? null : data.messagekey;
    self.message = !data ? null : data.message;
    self.createddate = !data ? null : data.createddate;
    self.respondentkey = !data ? null : data.respondentkey;
    self.type = !data ? null : data.type;
};

app.index.Models.MsgTypes = {
    Respondent: 1,
    Advertiser: 2
};

app.index.Models.Dialogue = function () {
    var self = this;
    self.rmsg = ko.observableArray();
    self.amsg = ko.observableArray();

    self.initMessages = function (data) {
        self.rmsg(ko.utils.arrayMap(data.rmsg, function(x) {
            return new app.index.Models.Message($.extend(x, { type : app.index.Models.MsgTypes.Respondent }));
        }));
        
        self.amsg(ko.utils.arrayMap(data.amsg, function(x) {
            return new app.index.Models.Message($.extend(x, { type : app.index.Models.MsgTypes.Advertiser }));
        }));
    };

    self.clearMessages = function () {
        self.rmsg([]);
        self.amsg([]);
    };

    self.addRmsg = function (x) {
        self.rmsg.push(new app.index.Models.Message($.extend(x, { type : app.index.Models.MsgTypes.Respondent })));
    };
    
    self.addAmsg = function (x) {
        self.amsg.push(new app.index.Models.Message($.extend(x, { type : app.index.Models.MsgTypes.Advertiser })));
    };
    
    self.msg = ko.computed(function () {
        var merger = self.rmsg().concat(self.amsg());
        
        merger.sort(function (x, y) {
            if (moment(x.createddate) === moment(y.createddate)) {
                return 0;
            } else if (moment(x.createddate) > moment(y.createddate)) {
                return -1;
            } else {
                return 1;
            }
        });
        
        return merger;
    });
};

app.index.Models.ServicesEnquiry = function (data) {
    var self = this;
    self.enquirykey = !data ? null : data.enquirykey;
    self.typekey = ko.observable(!data ? null : data.typekey);
    self.name = ko.observable(!data ? null : data.name);
    self.email = ko.observable(!data ? null : data.email);
    self.phone = ko.observable(!data ? null : data.phone);
    self.enquiry = ko.observable(!data ? null : data.enquiry);
    self.createddate = !data || !data.createddate ? utils.CustomDate() : data.createddate;
};

