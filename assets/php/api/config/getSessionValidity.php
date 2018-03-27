<?php
  /*  Include main.php  */
  include_once $_SERVER['DOCUMENT_ROOT'] . "/assets/php/main.php";

  $token = null;
  /*  Get the token  */
  if (isset(getallheaders()['Token'])) {
    $token = htmlspecialchars(strip_tags(trim(getallheaders()['Token'])));
  }
  $session = Session::getByToken($token);
  if($session->isValid()) {
    if($session->getUser()->isAdmin()) {
      dieInfos(array("sessionValidity" => $GLOBALS['config']['sessionValidity']));
    } else {
      dieCode(403);
    }
  } else {
    dieCode(301);
  }
