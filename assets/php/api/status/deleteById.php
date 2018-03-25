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
          //Check if any user has this Status
          $users = array();
          foreach (User::getAll() as $user) {
            if (User::getById($user["id"])->getStatus()->getId() == $id) {
              $users[] = User::getById($user["id"])->getInfos();
            }
          }
          if (!count($users) > 0) {
            Log::create("Delete Status", "successful", $session->getUser());
            Status::getById($id)->delete();
            dieSuccessful();
          } else {
            dieError(array("status is in use" => $users));
          }
        } else {
          Log::create("Delete Status", "id " . $id . " not found", $session->getUser());
          dieError("id not found");
        }
      } else {
        Log::create("Delete Status", "permission denied", $session->getUser());
        dieError("permission denied");
      }
    } else {
      dieError("invalid session");
    }
  } else {
    dieError("invalid request");
  }
