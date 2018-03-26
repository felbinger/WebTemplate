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
        if (Level::existById($id)) {
          if ($id == 0 || $id == 1) {
            dieError("this level is not deleteable");
          } else {
            //Check if any user has this level
            $users = array();
            foreach (User::getAll() as $user) {
              if (User::getById($user["id"])->getLevel()->getId() == $id) {
                $users[] = User::getById($user["id"])->getInfos();
              }
            }
            if (!count($users) > 0) {
              Log::create("Delete Level", "successful", $session->getUser());
              Level::getById($id)->delete();
              dieSuccessful();
            } else {
              dieError(array("level is in use" => $users));
            }
          }
        } else {
          Log::create("Delete Level", "id " . $id . " not found", $session->getUser());
          dieError("id not found");
        }
      } else {
        Log::create("Delete Level", "permission denied", $session->getUser());
        dieError("permission denied");
      }
    } else {
      dieError("invalid session");
    }
  } else {
    dieError("invalid request");
  }
