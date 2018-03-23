<?php
  class Session {
    private $id;
    private $user; //object of class User
    private $token;
    private $created;
    private $expire;
    private $broken;

    /*  Constructor  */
    private function __construct($id, $user, $token, $created, $expire, $broken) {
      $this->id = $id;
      $this->user = $user;
      $this->token = $token;
      $this->created = $created;
      $this->expire = $expire;
      $this->broken = $broken;
    }

    /*  Getter  */
    public function getId() {
      return $this->id;
    }
    public function getUser() {
      return $this->user;
    }
    public function getToken() {
      return $this->token;
    }
    public function getCreated() {
      return $this->created;
    }
    public function getExpire() {
      return $this->expire;
    }
    private function isBroken() {
      return $this->broken;
    }
    public function isValid() {
      return !$this->isBroken() && $this->getExpire() > new DateTime();
    }

    public function breakSession() {
      if(!$this->isBroken()) {
        $GLOBALS['db']->update("UPDATE `session` SET `broken` = 1 WHERE `id`= :id", array("id" => $this->getId()));
        $this->broken = true;
      }
    }

    /*  Generate Token  */
    public static function generateToken() {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $token = '';
      for ($i = 0; $i < 64; $i++) {
        $token .= $characters[rand(0, $charactersLength - 1)];
      }
      return $token;
    }

    public static function create($user) {
      $token = self::generateToken();
      $created = new DateTime();
      $expire = new DateTime();
      $expire->add(new DateInterval('PT' . $GLOBALS["config"]['sessionValidity'] . 'M'));
      //1000-01-01 00:00:00
      $data = array("user" => $user->getId(),
                    "token" => $token,
                    "created" => $created->format('Y-m-d H:i:s'),
                    "expire" => $expire->format('Y-m-d H:i:s'),
                    "broken" => (int) false);
      $GLOBALS['db']->update("INSERT INTO session(user, token, created, expire, broken)
                              VALUES(:user, :token, :created, :expire, :broken)", $data);
      $user->updateLastLogin();
      return self::getByToken($token);
    }

    public static function getByToken($token) {
      $arr = $GLOBALS['db']->query("SELECT * FROM `session` WHERE `token` = :token", array("token" => $token));
      return new Session($arr['id'],
                         User::getById($arr['user']),
                         $arr['token'],
                         new DateTime($arr['created']),
                         new DateTime($arr['expire']),
                         $arr['broken']);
    }

  }
