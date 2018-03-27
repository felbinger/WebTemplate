<?php
  /*  Include main.php  */
  include_once $_SERVER['DOCUMENT_ROOT'] . "/assets/php/main.php";

  $token = null;
  /*  Get the token  */
  if (isset(getallheaders()['Token'])) {
    $token = htmlspecialchars(strip_tags(trim(getallheaders()['Token'])));
  }

  $post_params = array("id",
                       "realname",
                       "email",
                       "level",
                       "status");
  include $_SERVER['DOCUMENT_ROOT'] . "/assets/php/utils/params.php";

  if($exec_post) {
    $session = Session::getByToken($token);
    $id = htmlspecialchars(strip_tags(trim($_POST['id'])));
    $realname = htmlspecialchars(strip_tags(trim($_POST['realname'])));
    $email = htmlspecialchars(strip_tags(trim($_POST['email'])));
    $level = htmlspecialchars(strip_tags(trim($_POST['level'])));
    $status = htmlspecialchars(strip_tags(trim($_POST['status'])));
    if($session->isValid()) {
      if($session->getUser()->isAdmin()) {
        if(User::existById($id)) {
          Log::create("Admin Update", "successful", $session->getUser());
          User::getById($id)->update($realname, $email, $level, $status);
          dieCode(200);
        } else {
          Log::create("Admin Update", "id " . $id . " not found", $session->getUser());
          dieCode(404);
        }
      } else {
        Log::create("Admin Update", "permission denied", $session->getUser());
        dieCode(403);
      }
    } else {
      dieCode(301);
    }
  } else {
    dieCode(400);
  }
