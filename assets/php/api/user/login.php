<?php
  /*  Include main.php  */
  include_once $_SERVER['DOCUMENT_ROOT'] . "/assets/php/main.php";

  $token = null;
  /*  Get the token  */
  if (isset(getallheaders()['Token'])) {
    $token = htmlspecialchars(strip_tags(trim(getallheaders()['Token'])));
  }

  $post_params = array("username",
                       "password");
  include $_SERVER['DOCUMENT_ROOT'] . "/assets/php/utils/params.php";
  if($exec_post) {
    $session = Session::getByToken($token);
    $username = htmlspecialchars(strip_tags(trim($_POST['username'])));
    $password = htmlspecialchars(strip_tags(trim($_POST['password'])));
    if ($session->isValid()) {
      dieError("already logged in");
    }

    if(User::existByName($username)) {
      if (User::verifyPassword($username, $password)) {
        Log::create("Login", "successful", User::getByName($username));
        dieInfos(array("token" => Session::create(User::getByName($username))->getToken()));
      } else {
        Log::create("Login", "wrong password", User::getByName($username));
        dieError("wrong credentials");
      }
    } else {
      Log::create("Login", "wrong username", User::getByName($username)); //TODO: User does not exist, could cause an error on the database (foreignkey)
      dieError("wrong credentials");
    }
  } else {
    dieError("invalid request");
  }
