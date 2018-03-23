var token = getCookie('token');

var Config = {
  getSessionValidity: function(callback) {
    requestWithToken("POST", host + "assets/php/api/config/getSessionValidity.php", "", function(response) {
      callback(JSON.parse(response)["sessionValidity"]);
    }, token);
  },
  setSessionValidity: function(validity) {
    requestWithToken("POST", host + "assets/php/api/config/setSessionValidity.php", "sessionValidity=" + validity, function(response) {}, token);
  }
}
