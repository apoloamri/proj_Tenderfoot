function Loading(bool) {
    if (bool) {
        EnableButtons(false);
        ShowElement("loading", true);
        document.body.style.cursor = "wait";
    }
    else {
        EnableButtons(true);
        ShowElement("loading", false);
        document.body.style.cursor = "default";
    }
}

function EnableButtons(bool) {
    $("button").prop("disabled", !bool);
    $("submit").prop("disabled", !bool);
}

function ShowElement(element, bool) {
    if (bool) {
        $("#" + element).show();
    }
    else {
        $("#" + element).hide();
    }
}

function Param(parameterName) {
    var result = "", tmp = [];
    location
        .search
        .substr(1)
        .split("&")
        .forEach(function (item) {
            tmp = item.split("=");
            if (tmp[0] === parameterName) {
                result = decodeURIComponent(tmp[1]);
            }
        });
    return decodeURI(result);
}