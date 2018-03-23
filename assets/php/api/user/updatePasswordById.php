<?php
  /*  Include main.php  */
  include_once $_SERVER['DOCUMENT_ROOT'] . "/assets/php/main.php";

  $token = null;

  /*  Get the token  */
  if (isset(getallheaders()['Token'])) {
    $token = htmlspecialchars(strip_tags(trim(getallheaders()['Token'])));
  }

  $post_params = array("id",
                       "password");
  include $_SERVER['DOCUMENT_ROOT'] . "/assets/php/utils/params.php";

  if($exec_post) {
    $session = Session::getByToken($token);
    $id = htmlspecialchars(strip_tags(trim($_POST['id'])));
    $password = htmlspecialchars(strip_tags(trim($_POST['password'])));
    if($session->isValid()) {
      if($session->getUser()->isAdmin()) {
        if(User::existById($id)) {
          Log::create("Admin Password Update", "successful", $session->getUser());
          User::getById($id)->updatePassword($password);
          dieSuccessful();
        } else {
          Log::create("Admin Password Update", "id " . $id . " not found", $session->getUser());
          dieError('id not found');
        }
      } else {
        Log::create("Admin Password Update", "permission denied", $session->getUser());
        dieError('permission denied');
      }
    } else {
      dieError('invalid request');
    }
  } else {
    dieError('invalid session');
  }
