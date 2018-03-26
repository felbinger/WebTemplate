var token = getCookie('token');

var Message = {
  getAll: function(callback) {
    requestWithToken("POST", host + "assets/php/api/message/getMyMessages.php", "", function(response) {
      callback(JSON.parse(response));
    }, token);
  },

  create: function(to, subject, message, callback) {
    requestWithToken("POST", host + "assets/php/api/message/create.php", "to=" + to + "&subject=" + subject + "&message=" + message, function(response) {
      callback(JSON.parse(response));
    }, token);
  },

  getById: function(id, callback) {
    requestWithToken("POST", host + "assets/php/api/message/getById.php", "id=" + id, function(response) {
      callback(JSON.parse(response));
    }, token);
  },

  delete: function(id, callback) {
    requestWithToken("POST", host + "assets/php/api/message/deleteById.php", "id=" + id, function(response) {
      callback(JSON.parse(response));
    }, token);
  }
}
