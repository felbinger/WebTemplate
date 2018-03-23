const host = window.location.protocol + "//" + window.location.host + "/";

function request(type, url, data, callback) {
	var xhr;
	if (window.XMLHttpRequest) {
        xhr = new XMLHttpRequest();
    } else {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }

	xhr.open(type, url, true);

	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.onload = function() {
		callback(xhr.response);
	};
	xhr.send(data);
}

function requestWithToken(type, url, data, callback, tokenStr) {
	var xhr;
	if (window.XMLHttpRequest) {
        xhr = new XMLHttpRequest();
    } else {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }

	xhr.open(type, url, true);

	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.setRequestHeader("Token", tokenStr);
	xhr.onload = function() {
		callback(xhr.response);
	};
  xhr.send(data);
}

function isEmpty(obj) {
  for(var prop in obj) {
    if(obj.hasOwnProperty(prop)) {
        return false;
    }
  }
  return true;
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function timeConverter(timestamp){
  var time = new Date(timestamp);
  return ("0" + time.getUTCDate()).slice(-2) + "." +
         ("0" + (time.getUTCMonth() + 1)).slice(-2) + "." +
         time.getUTCFullYear() + " " +
         ("0" + time.getHours()).slice(-2) + ":" +
         ("0" + time.getMinutes()).slice(-2) + ":" +
         ("0" + time.getSeconds()).slice(-2);
}
