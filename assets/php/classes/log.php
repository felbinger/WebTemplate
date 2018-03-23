<?php
  class Log {
    private $id;
    private $ip;
    private $userAgent;
    private $api;
    private $status;
    private $user;
    private $timestamp;

    /*  Constructor  */
    private function __construct($id, $ip, $userAgent, $api, $status, $user, $timestamp) {
      $this->id = $id;
      $this->ip = $ip;
      $this->userAgent = $userAgent;
      $this->api = $api;
      $this->status = $status;
      $this->user = $user;
      $this->timestamp = $timestamp;
    }

    /*  Getter  */
    public function getId() {
      return $this->id;
    }
    public function getIp() {
      return $this->ip;
    }
    public function getUserAgent() {
      return $this->userAgent;
    }
    public function getApi() {
      return $this->api;
    }
    public function getStatus() {
      return $this->status;
    }
    public function getUser() {
      return $this->user;
    }
    public function getTimestamp() {
      return $this->timestamp;
    }

    public function getInfos() {
      return array("id" => $this->getId(),
                   "ip" => $this->getIp(),
                   "userAgent" => $this->getUserAgent(),
                   "api" => $this->getApi(),
                   "status" => $this->getStatus(),
                   "user" => $this->getUser()->getInfos(),
                   "timestamp" => $this->getTimestamp());
    }

    public static function create($api, $status, $user) {
      if ($GLOBALS['config']['logging'] == false) {
        return null;
      } else {
        if (!isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
          $ip = $_SERVER['REMOTE_ADDR'];
        } else {
          $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        $data = array("ip" => $ip,
                      "userAgent" => $_SERVER['HTTP_USER_AGENT'],
                      "api" => $api,
                      "status" => $status,
                      "user" => $user->getId(),
                      "ts" => (new DateTime())->format('Y-m-d H:i:s'));
        $id = $GLOBALS['db']->update("INSERT INTO log(`ip`, `userAgent`, `api`, `status`, `user`, `timestamp`)
                                      VALUES(:ip, :userAgent, :api, :status, :user, :ts)", $data);
        return self::getById($id);
      }
    }

    public static function existById($id) {
      return is_array($GLOBALS['db']->query("SELECT `id` FROM log WHERE id = :id", array("id" => $id)));
    }

    public static function getById($id) {
      if (self::existById($id)) {
        $arr = $GLOBALS['db']->query("SELECT * FROM log WHERE id = :id", array("id" => $id));
        return new Log($arr['id'],
                       $arr['ip'],
                       $arr['userAgent'],
                       $arr['api'],
                       $arr['status'],
                       User::getById($arr['user']),
                       $arr['timestamp']);
      }
    }

    public static function getAll() {
      $arr = $GLOBALS['db']->queryAll("SELECT id FROM `log`", array());
      $data = array();
      foreach ($arr as $row) {
        $data[] = self::getById($row['id'])->getInfos();
      }
      return $data;
    }
  }
