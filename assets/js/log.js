var token = getCookie('token');

var Log = {
  isLoggingEnabled: function(callback) {
    requestWithToken("POST", host + "assets/php/api/log/isLoggingEnabled.php", "", function(response) {
      response = JSON.parse(response);
      callback(response["enabled"]);
    }, token);
  },

  setLogging: function(logging) {
    requestWithToken("POST", host + "assets/php/api/log/setLogging.php", "logging=" + logging, function(response) {}, token);
  },

  getAll: function(callback) {
    requestWithToken("POST", host + "assets/php/api/log/getAll.php", "", function(response) {
      callback(JSON.parse(response));
    }, token);
  }
}
