<?php
  /*  Include main.php  */
  include_once $_SERVER['DOCUMENT_ROOT'] . "/assets/php/main.php";

  $token = null;
  /*  Get the token  */
  if (isset(getallheaders()['Token'])) {
    $token = htmlspecialchars(strip_tags(trim(getallheaders()['Token'])));
  }

  $post_params = array("to", "subject", "message");
  include $_SERVER['DOCUMENT_ROOT'] . "/assets/php/utils/params.php";
  if($exec_post) {
    $session = Session::getByToken($token);
    $userid = htmlspecialchars(strip_tags(trim($_POST['to'])));
    $subject = htmlspecialchars(strip_tags(trim($_POST['subject'])));
    $message = htmlspecialchars(strip_tags(trim($_POST['message'])));
    if ($session->isValid()) {
      Message::create($session->getUser(), User::getById($userid), $subject, $message);
      dieSuccessful();
    } else {
      dieError("invalid session");
    }
  } else {
    dieError("invalid request");
  }
