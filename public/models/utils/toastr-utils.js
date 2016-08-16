function toastrClasses(typeOfToastr) {
    switch (typeOfToastr) {
        case 'error': {
            return { "positionClass": "toast-bottom-full-width", "timeOut": "30000", "closeButton": true };
        }
        case 'warning': {
            return { "positionClass": "toast-bottom-full-width", "timeOut": "30000", "closeButton": true };
        }
        default: {
            return { "positionClass": "toast-bottom-full-width", "timeOut": "10000", "closeButton": true };
        }
    }
}

function toastrError(msg, title) {
    toastr.clear();
    toastr.error(msg, title, toastrClasses('error'));
}

function toastrSuccess(msg, title) {
    toastr.clear();
    toastr.success(msg, title, toastrClasses('success'));
}

function toastrWarning(msg, title) {
    toastr.clear();
    toastr.warning(msg, title, toastrClasses('warning'));
}

function toastrInfo(msg, title) {
    toastr.clear();
    toastr.info(msg, title, toastrClasses('info'));
}

function list2str(list) {
    switch(list.length) {
        case 0: return ""; break;
        case 1: return list[0]; break;
        default: {
            var str = "<ul>";

            $.each(list, function (index, value) {
                str += "<li>" + value + "</li>";
            });

            str += "</ul>";

            return str;
            break;
        }
    }
}

function toastrErrorFromList(list, title) {
    toastr.clear();
    toastr.error(list2str(list), title, toastrClasses('error'));
}

function toastrSuccessFromList(list, title) {
    toastr.clear();
    toastr.success(list2str(list), title, toastrClasses('success'));
}

function toastrWarningFromList(list, title) {
    toastr.clear();
    toastr.warning(list2str(list), title, toastrClasses('warning'));
}

function toastrInfoFromList(list, title) {
    toastr.clear();
    toastr.info(list2str(list), title, toastrClasses('info'));
}