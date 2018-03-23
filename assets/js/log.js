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

  getAll: function() {
    requestWithToken("POST", host + "assets/php/api/log/getAll.php", "", function(response) {
      response = JSON.parse(response);
      if(!response.hasOwnProperty('error')) {
        for (var obj in response) {
          $('#logs').append("<tr>" +
                            "<td>" + response[obj]["id"] + "</td>" +
                            "<td>" + response[obj]["ip"] + "</td>" +
                            "<td>" + response[obj]["userAgent"] + "</td>" +
                            "<td>" + response[obj]["api"] + "</td>" +
                            "<td>" + response[obj]["status"] + "</td>" +
                            "<td>" + response[obj]["user"]["username"] + "</td>" +
                            "<td>" + timeConverter(response[obj]["timestamp"]) + "</td>" +
                            "</tr>");
        }
        $('#viewLogTable').DataTable();
      }
    }, token);
  }
}
