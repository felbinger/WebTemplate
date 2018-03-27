<?php
  /*  Include main.php  */
  include_once $_SERVER['DOCUMENT_ROOT'] . "/assets/php/main.php";

  $post_params = array("username",
                       "realname",
                       "email",
                       "password");
  include $_SERVER['DOCUMENT_ROOT'] . "/assets/php/utils/params.php";
  if($exec_post) {
    if ($config["registration"]) {
      $username = htmlspecialchars(strip_tags(trim($_POST['username'])));
      $realname = htmlspecialchars(strip_tags(trim($_POST['realname'])));
      $email = htmlspecialchars(strip_tags(trim($_POST['email'])));
      $password = htmlspecialchars(strip_tags(trim($_POST['password'])));
      if(!User::existByName($username)) {
        $user = User::create($username, $realname, $email, $password, 1, 1);
        Log::create("Created User", "successful", $user);
        if ($config["emailVerification"]) {
          //TODO Send email with verificationCode
          //$message = 'Hey,\nyou can activate your account by click the link below:\nhttps://example.de/assets/php/api/user/register/verifyEmail.php?email='.$user->getEmail().'&verificationCode='.$user->getVerificationCode().'If you haven\'t sign up, please ignore this email.';
			    //$smtp = Mail::factory('smtp', array ('host' => "ssl://strato.de", 'port' => "465", 'auth' => true, 'username' => "smtp_username", 'password' => "smtp_password"));
		  	  //$mail = $smtp->send($user->getEmail(), array ('From' => "noreply@example.de", 'To' => $user->getEmail(), 'Subject' => "WebTemplate Mail Verification"), $message);
        }
        dieCode(200);
      } else {
        dieCode(901);
      }
    } else {
      dieCode(905);
    }
  } else {
    dieCode(400);
  }
