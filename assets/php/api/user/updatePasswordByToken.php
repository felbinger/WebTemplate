<?php
  /*  Include main.php  */
  include_once $_SERVER['DOCUMENT_ROOT'] . "/assets/php/main.php";

  $token = null;

  /*  Get the token  */
  if (isset(getallheaders()['Token'])) {
    $token = htmlspecialchars(strip_tags(trim(getallheaders()['Token'])));
  }

  $post_params = array("password");
  include $_SERVER['DOCUMENT_ROOT'] . "/assets/php/utils/params.php";
  if($exec_post) {
    $session = Session::getByToken($token);
    $password = htmlspecialchars(strip_tags(trim($_POST['password'])));
    if ($session->isValid()) {
      if (User::existById($session->getUser()->getId())) {
        Log::create("Password Update", "successful", $session->getUser());
        $session->getUser()->updatePassword($password);
        dieSuccessful();
      } else {
        Log::create("Password Update", "id " . $session->getUser()->getId() . " not found", $session->getUser());
        dieError("id not found");
      }
    } else {
      dieError("invalid session");
    }
  } else {
    dieError("invalid request");
  }
