<?php
  /*  Include main.php  */
  include_once $_SERVER['DOCUMENT_ROOT'] . "/assets/php/main.php";

  $token = null;
  /*  Get the token  */
  if (isset(getallheaders()['Token'])) {
    $token = htmlspecialchars(strip_tags(trim(getallheaders()['Token'])));
  }

  $post_params = array("id");
  include_once $_SERVER['DOCUMENT_ROOT'] . "/assets/php/utils/params.php";
  if($exec_post) {
    $session = Session::getByToken($token);
    $id = htmlspecialchars(strip_tags(trim($_POST['id'])));
    if ($session->isValid()) {
      if($session->getUser()->isAdmin()) {
        if (User::existById($id)) {
          Log::create("Delete User", "successful", $session->getUser());
          User::getById($id)->delete();
          dieSuccessful();
        } else {
          Log::create("Delete User", "id " . $id . " not found", $session->getUser());
          dieError("id not found");
        }
      } else {
        Log::create("Delete User", "permission denied", $session->getUser());
        dieError("permission denied");
      }
    } else {
      dieError("invalid session");
    }
  } else {
    dieError("invalid request");
  }
