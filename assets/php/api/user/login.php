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
      dieCode(904);
    }
    if(User::existByName($username)) {
      if ($config["emailVerification"] && !User::getByName($username)->isEmailVerified()) {
        dieCode(903);
      }
      if (User::verifyPassword($username, $password)) {
        Log::create("Login", "successful", User::getByName($username));
        dieInfos(array("token" => Session::create(User::getByName($username))->getToken()));
      } else {
        Log::create("Login", "wrong password", User::getByName($username));
        dieCode(902);
      }
    } else {
      Log::create("Login", "wrong username", User::getByName($username));
      dieCode(902);
    }
  } else {
    dieCode(400);
  }
