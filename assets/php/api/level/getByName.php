<?php
  /*  Include main.php  */
  include_once $_SERVER['DOCUMENT_ROOT'] . "/assets/php/main.php";

  $token = null;
  /*  Get the token  */
  if (isset(getallheaders()['Token'])) {
    $token = htmlspecialchars(strip_tags(trim(getallheaders()['Token'])));
  }
  $post_params = array("name");
  include $_SERVER['DOCUMENT_ROOT'] . "/assets/php/utils/params.php";
  if($exec_post) {
    $session = Session::getByToken($token);
    $name = htmlspecialchars(strip_tags(trim($_POST["name"])));
    if($session->isValid()) {
      if(Level::existByName($name)) {
        dieInfos(Level::getByName($name)->getInfos());
      } else {
        dieError("name not found");
      }
    } else {
      dieError('invalid session');
    }
  } else {
    dieError('invalid request');
  }
