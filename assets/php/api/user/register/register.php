<?php
  /*  Include main.php  */
  include_once $_SERVER['DOCUMENT_ROOT'] . "/assets/php/main.php";

  $post_params = array("username",
                       "realname",
                       "email",
                       "password");
  include $_SERVER['DOCUMENT_ROOT'] . "/assets/php/utils/params.php";
  if($exec_post) {
    if ($config["registration"]) {
      $username = htmlspecialchars(strip_tags(trim($_POST['username'])));
      $realname = htmlspecialchars(strip_tags(trim($_POST['realname'])));
      $email = htmlspecialchars(strip_tags(trim($_POST['email'])));
      $password = htmlspecialchars(strip_tags(trim($_POST['password'])));
      if(!User::existByName($username)) {
        User::create($username, $realname, $email, $password, 0, 0);
        Log::create("Created User", "successful", User::getByName($username));
        dieSuccessful();
      } else {
        dieError("username already exists");
      }
    } else {
      dieError("registration is disabled");
    }
  } else {
    dieError("invalid request");
  }
