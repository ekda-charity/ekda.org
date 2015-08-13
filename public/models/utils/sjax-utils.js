$(function () {
    $.ajaxSetup({ cache: false });
});

function sjaxPost(url, dataToAdd, success, error, beforeSend, complete) {
    sjaxModify(url, dataToAdd, "POST", success, error, beforeSend, complete);
}

function sjaxPut(url, dataToUpdate, success, error, beforeSend, complete) {
    sjaxModify(url, dataToUpdate, "PUT", success, error, beforeSend, complete);
}

function sjaxDelete(url, dataToDelete, success, error, beforeSend, complete) {
    sjaxModify(url, dataToDelete, "DELETE", success, error, beforeSend, complete);
}

function sjaxGet(url, success, error, beforeSend, complete) {
    sjaxModify(url, null, "GET", success, error, beforeSend, complete);
}

function sjaxModify(url, dataToSend, httpVerb, success, error, beforeSend, complete) {
    $.ajax(url, {
        async: false,
        data: dataToSend,
        type: httpVerb,
        dataType: "json",
        contentType: "application/json",
        success: function (data, textStatus, jqXHR) {
            if (success) {
                success(data, textStatus, jqXHR);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            if (error) {
                error(jqXHR, textStatus, errorThrown);
            }
        },
        beforeSend: function (jqXHR, settings) {
            if (beforeSend) {
                beforeSend(jqXHR, settings);
            }
        },
        complete: function (jqXHR, textStatus) {
            if (complete) {
                complete(jqXHR, textStatus);
            }
        }
    });
}
