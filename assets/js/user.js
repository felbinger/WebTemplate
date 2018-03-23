var token = getCookie('token');

var User = {
    login: function(username, password) {
      request("POST", host + "assets/php/api/user/login.php", "username=" + username + "&password=" + CryptoJS.SHA512(password), function(response) {
        response = JSON.parse(response);
        if(!response.hasOwnProperty('error')) {
          setCookie('token', response['token'], 2);
          token = getCookie('token');
          window.location = "index.html";
        } else {
          $("#login_error").html('<div class="form-group"><div class="alert alert-danger"><span class="fa fa-info-circle"></span>' + ((response["error"] == "wrong credentials") ? " Wrong Credentials" : "Internal Error, try again later!") + '</div></div>');
        }
      });
    },

    /*  Check if the token in the cookie is valid, return's a boolean  */
    isTokenValid: function(callback) {
      requestWithToken("POST", host + "assets/php/api/user/isTokenValid.php", "", function(response) {
        callback(JSON.parse(response)["valid"]);
      }, token);
    },

    /*  Break the current session, set the cookie "token" to null and redirect to index.html  */
    logout: function() {
      requestWithToken("POST", host + "assets/php/api/user/logout.php", "", function(response) {
        setCookie('token', "null", -1);
        window.location = host + "index.html";
      }, token);
    },

    /*  Check if registration is enabled, used in register.html  */
    isRegistrationEnabled: function(callback) {
      requestWithToken("POST", host + "assets/php/api/user/register/isRegistrationEnabled.php", "", function(response) {
        callback(JSON.parse(response)["enabled"]);
      }, token);
    },

    /*  Set Registration to true/false  */
    setRegistration: function(registration) {
      requestWithToken("POST", host + "assets/php/api/user/register/setRegistration.php", "registration=" + registration, function(response) {}, token);
    },

    /*  Register a new user, used in register.html  */
    register: function(username, realname, email, password) {
      request("POST", host + "assets/php/api/user/register/register.php", "username=" + username + "realname=" + realname + "email=" + email +  "&password=" + CryptoJS.SHA512(password), function(response) {
        response = JSON.parse(response);
        if(!response.hasOwnProperty('error')) {
          window.location = "login.html";
        } else if (response["error"] === "registration is disabled") {
          $("#register_error").html('<div class="form-group"><div class="alert alert-danger"><span class="fa fa-info-circle"></span>Registration is disabled!</div></div>');
        } else {
          $("#register_error").html('<div class="form-group"><div class="alert alert-danger"><span class="fa fa-info-circle"></span>Internal Error, try again later!</div></div>');
        }
      }, token);
    },

    /*  Get informations about the current logged in user, return's an associative array  */
    /*  Used in: Settings  */
    get: function(callback) {
      requestWithToken("POST", host + "assets/php/api/user/get.php", "", function(response) {
        callback(JSON.parse(response));
      }, token);
    },

    /*  Update the current logged in user  */
    /*  Used in: Settings  */
    update: function(realname, email, callback) {
      requestWithToken("POST", host + "assets/php/api/user/updateByToken.php", "realname=" + realname + "&email=" + email, function(response) {
        callback(JSON.parse(response));
      }, token);
    },

    /*  Update the password of the current logged in user  */
    /*  Used in: Settings  */
    updatePassword: function(password, callback) {
      requestWithToken("POST", host + "assets/php/api/user/updatePasswordByToken.php", "password=" + CryptoJS.SHA512(password), function(response) {
        callback(JSON.parse(response));
      }, token);
    },

    /*  Get informations about an user by submitting the id, return's associative array  */
    /*  Used in: Dashboard  */
    getById: function(id, callback) {
      requestWithToken("POST", host + "assets/php/api/user/getById.php", "id=" + id, function(response) {
        callback(JSON.parse(response));
      }, token);
    },

    /*  Get all users and append them to the dashboard table  */
    /*  Used in: Dashboard  */
    getAll: function() {
      requestWithToken("POST", host + "assets/php/api/user/getAll.php", "", function(response) {
        response = JSON.parse(response);
        if(!response.hasOwnProperty('error')) {
          for (var obj in response) {
            $('#users').append("<tr>" +
                               "<td>" + response[obj]["username"] + "</td>" +
                               "<td>" + response[obj]["realname"] + "</td>" +
                               "<td>" + response[obj]["email"] + "</td>" +
                               "<td>" + timeConverter(response[obj]["created"]) + "</td>" +
                               "<td>" + ((response[obj]["lastlogin"] === "0000-00-00 00:00:00" || response[obj]["lastlogin"] == null) ? "Never" : timeConverter(response[obj]["lastlogin"])) + "</td>" +
                               "<td>" + response[obj]["level"]["name"] + "</td>" +
                               "<td>" + response[obj]["status"]["name"] + "</td>" +
                               "<td>" + '<a onclick="dashboardOpenModalUpdate(' + response[obj]["id"] + ')"><span class="fa fa-gear"/></a>' + "</td>" +
                               "<td>" + '<a onclick="dashboardUserDeleteFunction(' + response[obj]["id"] + ')"><span class="fa fa-trash-o"/></a>' +"</td>" +
                               "</tr>");
          }
          $('#viewUserTable').DataTable();
        }
      }, token);
    },

    /*  Create an new user  */
    /*  Used in: Dashboard  */
    create: function(username, realname, email, password, level, status, callback) {
      requestWithToken("POST", host + "assets/php/api/user/create.php", "username=" + username + "&realname=" + realname + "&email=" + email + "&password=" + CryptoJS.SHA512(password) + "&level=" + level + "&status=" + status, function(response) {
        callback(JSON.parse(response));
      }, token);
    },

    /*  Delete an user by submitting the id  */
    /*  Used in: Dashboard  */
    delete: function(id, callback) {
      requestWithToken("POST", host + "assets/php/api/user/deleteById.php", "id=" + id, function(response) {
        callback(JSON.parse(response));
      }, token);
    },

    /*  Update an user by submitting the id  */
    /*  Used in: Dashboard  */
    updateById: function(id, realname, email, level, status, callback) {
      requestWithToken("POST", host + "assets/php/api/user/updateById.php", "id=" + id + "&realname=" + realname + "&email=" + email + "&level=" + level + "&status=" + status, function(response) {
        callback(JSON.parse(response));
      }, token);
    },

    /*  Update the password of an user by submitting the id  */
    /*  Used in: Dashboard  */
    updatePasswordById: function(id, password, callback) {
      requestWithToken("POST", host + "assets/php/api/user/updatePasswordById.php", "id=" + id + "&password=" + CryptoJS.SHA512(password), function(response) {
        callback(JSON.parse(response));

      }, token);
    }
};
