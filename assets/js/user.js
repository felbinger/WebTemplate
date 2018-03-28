var token = getCookie('token');

var User = {
    login: function(username, password, callback) {
      request("POST", host + "assets/php/api/user/login.php", "username=" + username + "&password=" + CryptoJS.SHA512(password), function(response) {
        callback(JSON.parse(response));
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
    register: function(username, realname, email, password, callback) {
      request("POST", host + "assets/php/api/user/register/register.php", "username=" + username + "&realname=" + realname + "&email=" + email +  "&password=" + CryptoJS.SHA512(password), function(response) {
        callback(JSON.parse(response));
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
    getAll: function(callback) {
      requestWithToken("POST", host + "assets/php/api/user/getAll.php", "", function(response) {
        callback(JSON.parse(response));
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
    },

    /*  Get the realname of an user without administrator privileges */
    /*  Used in Messages  */
    getRealnameById: function(id, callback) {
      requestWithToken("POST", host + "assets/php/api/user/getRealnameById.php", "id=" + id, function(response) {
        callback(JSON.parse(response)["realname"]);
      }, token);
    },

    /*  Get the realname from all users without administrator privileges - used to select how to which user the message should be send  */
    /*  Used in Messages  */
    getAllRealnames: function(callback) {
      requestWithToken("POST", host + "assets/php/api/user/getAllRealnames.php", "", function(response) {
        callback(JSON.parse(response));
      }, token);
    }
};
