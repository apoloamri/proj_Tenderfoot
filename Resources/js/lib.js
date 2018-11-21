export default {
    //COMMON
    ArrayContains: function (array, item) {
        for(var i = 0; i < array.length; i++){
            if (array[i] == item) {
                return true
            }
        }
        return false
    },
    Delay: (function () {
        var timer = 0;
        return function(callback, ms){
          clearTimeout (timer);
          timer = setTimeout(callback, ms);
        };
    })(),
    IsEmptyOrSpaces: function (input) {
        return input == null || input.toString().replace(/\s/g, '').length < 1;
    },
    UrlPart: function (urlPart) {
        return decodeURI(window.location.pathname.split("/")[urlPart]);
    },
    UrlParameter: function (parameterName) {
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
    },
    //COMMON

    //COOKIES
    SetCookie: function (cname, cvalue, exdays) {
        SetCookie(cname, cvalue, exdays);
    },
    GetCookie: function (cname) {
        return GetCookie(cname);
    },
    DeleteCookies: function () {
        DeleteCookies();
    },
    //COOKIES

    //MODAL
    ShowModal: function (modalName) {
        if (modalName != null && modalName != "") {
            $("#" + modalName).removeClass("unshow");
        }
        else {
            $(".modal").removeClass("unshow");
        }
        $("#modalShadow").removeClass("unshow");
    },
    HideModal: function (modalName) {
        if (modalName != null && modalName != "") {
            $("#" + modalName).addClass("unshow");
        }
        else {
            $(".modal").addClass("unshow");
        }
        $("#modalShadow").addClass("unshow");
    },
    InitialLoading: function (bool) {
        if (bool) {
            this.EnableButtons(false);
            this.ShowElement("loading", true);
            document.body.style.cursor = "wait";
        }
        else {
            this.EnableButtons(true);
            this.ShowElement("loading", false);
            document.body.style.cursor = "default";
        }
    },
    EnableButtons: function (bool) {
        $("button").prop("disabled", !bool);
        $("submit").prop("disabled", !bool);
    },
    ShowElement: function (element, bool) {
        if (bool) {
            $("#" + element).show();
        }
        else {
            $("#" + element).hide();
        }
    },
    //MODAL
    
    //REQUESTS
    Get: function (url, data, success, error) {
        AjaxCall("GET", url, data, success, error);
    },
    Post: function (url, data, success, error) {
        AjaxCall("POST", url, data, success, error);
    },
    Put: function (url, data, success, error) {
        AjaxCall("PUT", url, data, success, error);
    },
    Delete: function (url, data, success, error) {
        AjaxCall("DELETE", url, data, success, error);
    },
    Form: function (url, data, success, error) {
        AjaxCall("POST", url, data, success, error, false, true);
    },
    Authenticate: function (data) {
        var returnData = false;
        AjaxCall("POST", configuration.Authenticate, data,
            function (response) {
                SetCookie("token", response.token, 1);
                returnData = response;
            }, 
            function () { 
                returnData = false;
            }, false, true);
        return returnData;
    },
    CheckAuthentication: function (success, error) {
        var data = { "token": GetCookie("token") };
        AjaxCall("GET", configuration.CheckAuthentication, data, success, error);
    },
    //REQUESTS
}

function SetCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
function GetCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(";");
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === " ") {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
function DeleteCookies() {
    document.cookie
        .split(";")
        .forEach(function (c) {
            document.cookie = c.replace(/^ +/, "")
                .replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
        });
}
function AjaxCall(
    type, 
    url, 
    data, 
    success, 
    error, 
    async = true, 
    formData = false) 
{
    var headers = { };
    var processData = true;
    var contentType = "application/json";
    if (formData == true) {
        var formData = new FormData();
        for (var key in data) {
            formData.append(key, data[key]);
        }
        data = formData;
        processData = false;
        contentType = false;
    } 
    else if (data != null) { 
        if (type == "GET") {
            data = $.param(data);
        }
        else {
            data = JSON.stringify(data);
        }
    }
    var token = GetCookie("token");
    if (token) {
        headers = {
            "Authorization": "Bearer " + token
        }
    }
    $.ajax({
        headers: headers,
        async: async,
        type: type,
        url: url,
        processData: processData,
        contentType: contentType,
        dataType: "json",
        data: data,
        success: success,
        error: error
    });
}