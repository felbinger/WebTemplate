<?php
  /*  Include main.php  */
  include_once $_SERVER['DOCUMENT_ROOT'] . "/assets/php/main.php";

  $token = null;
  /*  Get the token  */
  if (isset(getallheaders()['Token'])) {
    $token = htmlspecialchars(strip_tags(trim(getallheaders()['Token'])));
  }

  $post_params = array("name");
  include $_SERVER['DOCUMENT_ROOT'] . "/assets/php/utils/params.php";
  if($exec_post) {
    $session = Session::getByToken($token);
    $name = htmlspecialchars(strip_tags(trim($_POST['name'])));
    if ($session->isValid()) {
      if($session->getUser()->isAdmin()) {
        if(!Level::existByName($name)) {
          Log::create("Create Status", "successful", $session->getUser());
          Status::create($name);
          dieSuccessful();
        } else {
          Log::create("Create Status", "name " . $name . " already in use", $session->getUser());
          dieError("statusname already exists");
        }
      } else {
        Log::create("Create Status", "permission denied", $session->getUser());
        dieError("permission denied");
      }
    } else {
      dieError("invalid session");
    }
  } else {
    dieError("invalid request");
  }
