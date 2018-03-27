<?php
  /*  Include main.php  */
  include_once $_SERVER['DOCUMENT_ROOT'] . "/assets/php/main.php";

  $token = null;
  /*  Get the token  */
  if (isset(getallheaders()['Token'])) {
    $token = htmlspecialchars(strip_tags(trim(getallheaders()['Token'])));
  }

  $post_params = array("sessionValidity");
  include $_SERVER['DOCUMENT_ROOT'] . "/assets/php/utils/params.php";
  if($exec_post) {
    $session = Session::getByToken($token);
    $sessionValidity = htmlspecialchars(strip_tags(trim($_POST['sessionValidity'])));
    if ($session->isValid()) {
      if($session->getUser()->isAdmin()) {
        $db->update('UPDATE config SET value = :value WHERE name = "sessionValidity"', array("value" => $sessionValidity));
        dieCode(200);
      } else {
        dieCode(403);
      }
    } else {
      dieCode(301);
    }
  } else {
    dieCode(400);
  }
