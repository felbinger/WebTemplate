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
        if (Status::existById($id)) {
          //Check if any user has this status
          $users = array();
          foreach (User::getAll() as $user) {
            if (User::getById($user["id"])->getStatus()->getId() == $id) {
              $users[] = User::getById($user["id"])->getInfos();
            }
          }
          if (!count($users) > 0) {
            Log::create("Delete Status", "successful", $session->getUser());
            Status::getById($id)->delete();
            dieCode(200);
          } else {
            dieCode(907, $users));
          }
        } else {
          Log::create("Delete Status", "id " . $id . " not found", $session->getUser());
          dieCode(404);
        }
      } else {
        Log::create("Delete Status", "permission denied", $session->getUser());
        dieCode(403);
      }
    } else {
      dieCode(301);
    }
  } else {
    dieCode(400);
  }
