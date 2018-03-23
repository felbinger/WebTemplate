var token = getCookie('token');

var Status = {
  getAll: function(callback) {
    requestWithToken("POST", host + "assets/php/api/status/getAll.php", "", function(response) {
      callback(JSON.parse(response));
    }, token);
  },

  /*  Create an new status  */
  /*  Used in: Config  */
  create: function(name, callback) {
    requestWithToken("POST", host + "assets/php/api/status/create.php", "name=" + name, function(response) {
      callback(JSON.parse(response));
    }, token);
  },

  getById: function(id, callback) {
    requestWithToken("POST", host + "assets/php/api/status/getById.php", "id=" + id, function(response) {
      callback(JSON.parse(response));
    }, token);
  },

  updateById: function(id, name, callback) {
    requestWithToken("POST", host + "assets/php/api/status/updateById.php", "id=" + id + "&name=" + name, function(response) {
      callback(JSON.parse(response));
    }, token);
  },

  delete: function(id, callback) {
    requestWithToken("POST", host + "assets/php/api/status/deleteById.php", "id=" + id, function(response) {
      callback(JSON.parse(response));
    }, token);
  }
}
