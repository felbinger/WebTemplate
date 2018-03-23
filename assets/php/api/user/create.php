<?php
  /*  Include main.php  */
  include_once $_SERVER['DOCUMENT_ROOT'] . "/assets/php/main.php";

  $token = null;
  /*  Get the token  */
  if (isset(getallheaders()['Token'])) {
    $token = htmlspecialchars(strip_tags(trim(getallheaders()['Token'])));
  }

  $post_params = array("username",
                       "realname",
                       "email",
                       "password",
                       "level",
                       "status");
  include $_SERVER['DOCUMENT_ROOT'] . "/assets/php/utils/params.php";
  if($exec_post) {
    $session = Session::getByToken($token);
    $username = htmlspecialchars(strip_tags(trim($_POST['username'])));
    $realname = htmlspecialchars(strip_tags(trim($_POST['realname'])));
    $email = htmlspecialchars(strip_tags(trim($_POST['email'])));
    $password = htmlspecialchars(strip_tags(trim($_POST['password'])));
    $level = htmlspecialchars(strip_tags(trim($_POST['level'])));
    $status = htmlspecialchars(strip_tags(trim($_POST['status'])));
    if ($session->isValid()) {
      if($session->getUser()->isAdmin()) {
        if(!User::existByName($username)) {
          Log::create("Created User", "successful", $session->getUser());
          User::create($username, $realname, $email, $password, $level, $status);
          dieSuccessful();
        } else {
          Log::create("Created User", "username " . $username . " already used", $session->getUser());
          dieError("username already exists");
        }
      } else {
        Log::create("Created User", "permission denied", $session->getUser());
        dieError("permission denied");
      }
    } else {
      dieError("invalid session");
    }
  } else {
    dieError("invalid request");
  }
