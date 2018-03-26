<?php
  /*  Include main.php  */
  include_once $_SERVER['DOCUMENT_ROOT'] . "/assets/php/main.php";

  $token = null;
  /*  Get the token  */
  if (isset(getallheaders()['Token'])) {
    $token = htmlspecialchars(strip_tags(trim(getallheaders()['Token'])));
  }
  $session = Session::getByToken($token);
  $emailVerification = htmlspecialchars(strip_tags(trim($_POST['emailVerification'])));
  if ($session->isValid()) {
    if($session->getUser()->isAdmin()) {
      dieInfos(array("enabled" => $config["emailVerification"]));
    } else {
      dieError("permission denied");
    }
  } else {
    dieError("invalid session");
  }
