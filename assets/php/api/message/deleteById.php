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
      if (Message::existById($id)) {
        if (Message::getById($id)->getTo() == $session->getUser()->getId()) {
          //CHECK IF the userid is in the fromid
          Message::getById($id)->delete();
          dieSuccessful();
        } else {
          Log::create("Delete Message", "tried to delete a message from another user", $session->getUser());
          dieError("you can only delete your own messages");
        }
      } else {
        Log::create("Delete Message", "id " . $id . " not found", $session->getUser());
        dieError("id not found");
      }
    } else {
      dieError("invalid session");
    }
  } else {
    dieError("invalid request");
  }
