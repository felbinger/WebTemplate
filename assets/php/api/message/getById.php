<?php
  /*  Include main.php  */
  include_once $_SERVER['DOCUMENT_ROOT'] . "/assets/php/main.php";

  $token = null;
  /*  Get the token  */
  if (isset(getallheaders()['Token'])) {
    $token = htmlspecialchars(strip_tags(trim(getallheaders()['Token'])));
  }
  $post_params = array("id");
  include $_SERVER['DOCUMENT_ROOT'] . "/assets/php/utils/params.php";
  if($exec_post) {
    $session = Session::getByToken($token);
    $id = htmlspecialchars(strip_tags(trim($_POST["id"])));
    if($session->isValid()) {
      if(Message::existById($id)) {
        dieInfos(Message::getById($id)->getInfos());
      } else {
        dieCode(404);
      }
    } else {
      dieCode(301);
    }
  } else {
    dieCode(400);
  }
