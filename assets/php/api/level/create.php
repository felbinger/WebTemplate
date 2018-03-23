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
          Log::create("Create Level", "successful", $session->getUser());
          Level::create($name);
          dieSuccessful();
        } else {
          Log::create("Create Level", "name " . $name . " already in use", $session->getUser());
          dieError("levelname already exists");
        }
      } else {
        Log::create("Create Level", "permission denied", $session->getUser());
        dieError("permission denied");
      }
    } else {
      dieError("invalid session");
    }
  } else {
    dieError("invalid request");
  }
