<?php
  /*  Include main.php  */
  include_once $_SERVER['DOCUMENT_ROOT'] . "/assets/php/main.php";

  $get_params = array("email",
                      "verificationCode");
  include $_SERVER['DOCUMENT_ROOT'] . "/assets/php/utils/params.php";
  if($exec_get) {
    $email = htmlspecialchars(strip_tags(trim($_GET['email'])));
    $verificationCode = htmlspecialchars(strip_tags(trim($_GET['verificationCode'])));
    if(User::existByEMail($email)) {
      if (User::verifyVerificationCode($email, $verificationCode)) {
        User::getByEMail($email)->setEmailVerifiedTrue();
        Log::create("Email Verification", "successful", User::getByEMail($email));
        dieSuccessful();
      } else {
        Log::create("Email Verification", "wrong verification code", User::getByEMail($email));
        dieError("invalid data");
      }
    } else {
      Log::create("Email Verification", "wrong email", User::getByEMail($email));
      dieError("invalid data");
    }
  } else {
    dieError("invalid request");
  }
