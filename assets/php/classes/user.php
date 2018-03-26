<?php
  class User {
    private $id;
    private $username;
    private $realname;
    private $email;
    private $verificationCode;
    private $emailVerified;
    private $password;
    private $lastlogin;
    private $created;
    private $level; //object of class Level
    private $status; //object of class Status

    /*  Constructor  */
    private function __construct($id, $username, $realname, $email, $verificationCode, $emailVerified, $password, $created, $lastlogin, $level, $status) {
      $this->id = $id;
      $this->username = $username;
      $this->realname = $realname;
      $this->email = $email;
      $this->verificationCode = $verificationCode;
      $this->emailVerified = $emailVerified;
      $this->password = $password;
      $this->created = $created;
      $this->lastlogin = $lastlogin;
      $this->level = $level;
      $this->status = $status;
    }

    /*  Getter  */
    public function getId() {
      return $this->id;
    }
    public function getUsername() {
      return $this->username;
    }
    public function getRealname() {
      return $this->realname;
    }
    public function getEmail() {
      return $this->email;
    }
    public function getVerificationCode() {
      return $this->verificationCode;
    }
    public function isEmailVerified() {
      return $this->emailVerified;
    }
    private function getPassword() {
      return $this->password;
    }
    public function getLastlogin() {
      return $this->lastlogin;
    }
    public function getCreated() {
      return $this->created;
    }
    public function getLevel() {
      return $this->level;
    }
    public function getStatus() {
      return $this->status;
    }

    /*  Function to get all infos (excluding password) about an user  */
    public function getInfos() {
      return array("id" => $this->getId(),
                   "username" => $this->getUsername(),
                   "realname" => $this->getRealname(),
                   "email" => $this->getEmail(),
                   "emailVerified" => $this->isEmailVerified(),
                   "created" => $this->getCreated(),
                   "lastlogin" => $this->getLastlogin(),
                   "level" => $this->getLevel()->getInfos(),
                   "status" => $this->getStatus()->getInfos());
    }

    public function getMinInfos() {
      return array("id" => $this->getId(),
                   "realname" => $this->getRealname());
    }

    /*  Function to delete an existing user account.  */
    public function delete() {
      $GLOBALS['db']->update("DELETE FROM user WHERE id = :id", array("id" => $this->getId()));
    }

    /*  Function to update an existing user acccount. You can only update the realname, email, level and the status (active, inactive)  */
    public function update($realname, $email, $level, $status) {
      $data = array("realname" => $realname,
                    "email" => $email,
                    "level" => $level,
                    "status" => $status,
                    "id" => $this->getId());
      $GLOBALS['db']->update("UPDATE user SET realname = :realname, email = :email, level = :level, status = :status WHERE id = :id", $data);

      $this->realname = $realname;
      $this->email = $email;
      $this->level = Level::getById($level);
      $this->status = $status;
    }

    /*  Function to update the password of an existing user account. */
    public function updatePassword($password) {
      $data = array("id" => $this->getId(),
                    "password" => $password);
      $GLOBALS['db']->update("UPDATE user SET password = :password WHERE id = :id", $data);
      $this->password = $password;
    }

    public function updateLastLogin() {
      $lastlogin = new DateTime();
      $data = array("id" => $this->getId(),
                    "lastlogin" => $lastlogin->format('Y-m-d H:i:s'));
      $GLOBALS['db']->update("UPDATE user SET lastlogin = :lastlogin WHERE id = :id", $data);
      $this->lastlogin = $lastlogin;
    }

    public function isAdmin() {
      return ($this->getLevel()->getId() == 0);
    }

    public function setEmailVerifiedTrue() {
      $GLOBALS['db']->update("UPDATE user SET emailVerified = 1 WHERE id = :id", array("id" => $this->getId()));
      $this->emailVerified = true;
    }

    public function getMyMessage() {
      return Message::getAllFor($this);
    }

    /*  Function to generate the verificationCode for emails  */
    public static function generateVerificationCode() {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $token = '';
      for ($i = 0; $i < 32; $i++) {
        $token .= $characters[rand(0, $charactersLength - 1)];
      }
      return $token;
    }

    /*  Function to create new users  */
    public static function create($username, $realname, $email, $password, $level, $status) {
      /*  Check if username already exist, if yes return false   */
      if(!self::existByName($username)) {
        $data = array("username" => $username,
                      "realname" => $realname,
                      "email" => $email,
                      "verificationCode" => self::generateVerificationCode(),
                      "password" => $password,
                      "level" => $level,
                      "status" => $status);
        $id = $GLOBALS['db']->update("INSERT INTO user(username, realname, email, verificationCode, password, level, status)
                                      VALUES(:username, :realname, :email, :verificationCode, :password, :level, :status)", $data);
        return self::getById($id);
      } else {
        return false;
      }
    }

    /*  Get the user from the database using id  */
    public static function getById($id) {
      if (self::existById($id)) {
        $arr = $GLOBALS['db']->query("SELECT * FROM user WHERE id = :id", array("id" => $id));
        return new User($arr['id'],
                        $arr['username'],
                        $arr['realname'],
                        $arr['email'],
                        $arr['verificationCode'],
                        (($arr['emailVerified'] == 0) ? false : true),
                        $arr['password'],
                        $arr['created'],
                        $arr['lastlogin'],
                        Level::getById($arr['level']),
                        Status::getById($arr['status']));
      }
    }

    /*  Get the user from the database  */
    public static function getByName($username) {
      if (self::existByName($username)) {
        $arr = $GLOBALS['db']->query("SELECT * FROM user WHERE username = :username", array("username" => $username));
        return new User($arr['id'],
                        $arr['username'],
                        $arr['realname'],
                        $arr['email'],
                        $arr['verificationCode'],
                        (($arr['emailVerified'] == 0) ? false : true),
                        $arr['password'],
                        $arr['created'],
                        $arr['lastlogin'],
                        Level::getById($arr['level']),
                        Status::getById($arr['status']));
      }
    }

    /*  Get the user from the database  */
    public static function getByEMail($email) {
      if (self::existByEMail($email)) {
        $arr = $GLOBALS['db']->query("SELECT * FROM user WHERE email = :email", array("email" => $email));
        return new User($arr['id'],
                        $arr['username'],
                        $arr['realname'],
                        $arr['email'],
                        $arr['verificationCode'],
                        (($arr['emailVerified'] == 0) ? false : true),
                        $arr['password'],
                        $arr['created'],
                        $arr['lastlogin'],
                        Level::getById($arr['level']),
                        Status::getById($arr['status']));
      }
    }

    /*  Check if the user account exist by id */
    public static function existById($id) {
      return is_array($GLOBALS['db']->query("SELECT `id` FROM user WHERE id = :id", array("id" => $id)));
    }

    /*  Check if the user account exist by username */
    public static function existByName($username) {
      return is_array($GLOBALS['db']->query("SELECT `id` FROM user WHERE username = :username", array("username" => $username)));
    }

    /*  Check if the user account exist by email */
    public static function existByEMail($email) {
      return is_array($GLOBALS['db']->query("SELECT `id` FROM user WHERE email = :email", array("email" => $email)));
    }

    /*  Verify the password of the account  */
    public static function verifyPassword($username, $password) {
      if (self::existByName($username)) {
        return ($GLOBALS['db']->query("SELECT `password` FROM user WHERE username = :username", array("username" => $username))['password'] === $password);
      }
    }

    /*  Verify the email verification code of the account  */
    public static function verifyVerificationCode($email, $verificationCode) {
      if (self::existByEMail($email)) {
        return ($GLOBALS['db']->query("SELECT `verificationCode` FROM user WHERE email = :email", array("email" => $email))['verificationCode'] === $verificationCode);
      }
    }

    /*  Get all users, for the dashboard  */
    public static function getAll() {
      $arr = $GLOBALS['db']->queryAll("SELECT id FROM `user`", array());
      $data = array();
      foreach ($arr as $row) {
        $data[] = self::getById($row['id'])->getInfos();
      }
      return $data;
    }

    public static function getAllRealnames() {
      $arr = $GLOBALS['db']->queryAll("SELECT id, realname FROM `user`", array());
      $data = array();
      foreach ($arr as $row) {
        $data[] = array("id" => $row['id'], "realname" => self::getById($row['id'])->getRealname());
      }
      return $data;
    }
  }
