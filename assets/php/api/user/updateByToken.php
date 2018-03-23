<?php
  /*  Include main.php  */
  include_once $_SERVER['DOCUMENT_ROOT'] . "/assets/php/main.php";

  $token = null;
  /*  Get the token  */
  if (isset(getallheaders()['Token'])) {
    $token = htmlspecialchars(strip_tags(trim(getallheaders()['Token'])));
  }

  $post_params = array("realname",
                       "email");
  include $_SERVER['DOCUMENT_ROOT'] . "/assets/php/utils/params.php";
  if($exec_post) {
    $session = Session::getByToken($token);
    $realname = htmlspecialchars(strip_tags(trim($_POST['realname'])));
    $email = htmlspecialchars(strip_tags(trim($_POST['email'])));
    if($session->isValid()) {
      if(User::existById($session->getUser()->getId())) {
        Log::create("Update", "successful", $session->getUser());
        User::getById($session->getUser()->getId())->update($realname, $email, $session->getUser()->getLevel()->getId(), $session->getUser()->getStatus()->getId());
        dieSuccessful();
      } else {
        Log::create("Update", "id " . $session->getUser()->getId() . " not found", $session->getUser());
        dieError('id not found');
      }
    } else {
      dieError('invalid session');
    }
  } else {
    dieError('invalid request');
  }
