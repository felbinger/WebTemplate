<?php
  class Message {
    private $id;
    private $from; //object of class User
    private $to; //object of class User
    private $subject;
    private $message;
    private $created;
    private $read;
    private $readTimestamp;

    /*  Constructor  */
    private function __construct($id, $from, $to, $subject, $message, $created, $read, $readTimestamp) {
      $this->id = $id;
      $this->from = $from;
      $this->to = $to;
      $this->subject = $subject;
      $this->message = $message;
      $this->created = $created;
      $this->read = $read;
      $this->readTimestamp = $readTimestamp;
    }

    /*  Getter  */
    public function getId() {
      return $this->id;
    }
    public function getFrom() {
      return $this->from;
    }
    public function getTo() {
      return $this->to;
    }
    public function getSubject() {
      return $this->subject;
    }
    public function getMessage() {
      return $this->message;
    }
    public function getCreated() {
      return $this->created;
    }
    public function isRead() {
      return $this->read;
    }
    public function getReadTimestamp() {
      return $this->readTimestamp;
    }

    public function getInfos() {
      return array("id" => $this->getId(),
                   "from" => User::getById($this->getFrom())->getMinInfos(),
                   "to" => User::getById($this->getTo())->getMinInfos(),
                   "subject" => $this->getSubject(),
                   "message" => $this->getMessage(),
                   "created" => $this->getCreated(),
                   "read" => $this->isRead(),
                   "readTimestamp" => $this->getReadTimestamp());
    }

    public function delete() {
      $GLOBALS['db']->update("DELETE FROM message WHERE id = :id", array("id" => $this->getId()));
    }

    public function setRead() {
      $GLOBALS['db']->update('UPDATE message SET read = 1 WHERE id = :id', array("id" => $this->getId()));
      $this->read = true;
    }

    public static function create($from, $to, $subject, $message) {
      $data = array("msgFrom" => $from->getId(),
                    "msgTo" => $to->getId(),
                    "subject" => $subject,
                    "message" => $message);
      $id = $GLOBALS['db']->update("INSERT INTO message(msgFrom, msgTo, subject, message) VALUES(:msgFrom, :msgTo, :subject, :message)", $data);
      return self::getById($id);
    }

    public static function existById($id) {
      return is_array($GLOBALS['db']->query("SELECT `id` FROM message WHERE id = :id", array("id" => $id)));
    }

    public static function getById($id) {
      if (self::existById($id)) {
        $arr = $GLOBALS['db']->query("SELECT * FROM message WHERE id = :id", array("id" => $id));
        return new Message($arr['id'],
                           $arr['msgFrom'],
                           $arr['msgTo'],
                           $arr['subject'],
                           $arr['message'],
                           $arr['created'],
                           (($arr['msgRead'] == 0) ? false : true),
                           $arr['readTimestamp']);
      }
    }

    public static function getAllFor($user) {
      $arr = $GLOBALS['db']->queryAll("SELECT id FROM message WHERE msgTo = :msgTo", array("msgTo" => $user->getId()));
      $data = array();
      foreach ($arr as $row) {
        $data[] = self::getById($row['id'])->getInfos();
      }
      return $data;
    }
  }
