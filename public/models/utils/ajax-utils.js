$(function () {
    $.ajaxSetup({ cache: false });
});

function ajaxPost(url, dataToAdd, success, error, beforeSend, complete) {
    ajaxModify(url, dataToAdd, "POST", success, error, beforeSend, complete);
}

function ajaxPut(url, dataToUpdate, success, error, beforeSend, complete) {
    ajaxModify(url, dataToUpdate, "PUT", success, error, beforeSend, complete);
}

function ajaxDelete(url, dataToDelete, success, error, beforeSend, complete) {
    ajaxModify(url, dataToDelete, "DELETE", success, error, beforeSend, complete);
}

function ajaxGet(url, success, error, beforeSend, complete) {
    ajaxModify(url, null, "GET", success, error, beforeSend, complete);
}

function ajaxModify(url, dataToSend, httpVerb, success, error, beforeSend, complete) {
    $.ajax(url, {
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
