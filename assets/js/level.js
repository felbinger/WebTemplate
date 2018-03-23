var token = getCookie('token');

var Level = {
  getAll: function(callback) {
    requestWithToken("POST", host + "assets/php/api/level/getAll.php", "", function(response) {
      callback(JSON.parse(response));
    }, token);
  },

  /*  Create an new level  */
  /*  Used in: Config  */
  create: function(name, callback) {
    requestWithToken("POST", host + "assets/php/api/level/create.php", "name=" + name, function(response) {
      callback(JSON.parse(response));
    }, token);
  },

  getById: function(id, callback) {
    requestWithToken("POST", host + "assets/php/api/level/getById.php", "id=" + id, function(response) {
      callback(JSON.parse(response));
    }, token);
  },

  updateById: function(id, name, callback) {
    requestWithToken("POST", host + "assets/php/api/level/updateById.php", "id=" + id + "&name=" + name, function(response) {
      callback(JSON.parse(response));
    }, token);
  },

  delete: function(id, callback) {
    requestWithToken("POST", host + "assets/php/api/level/deleteById.php", "id=" + id, function(response) {
      callback(JSON.parse(response));
    }, token);
  }
}
