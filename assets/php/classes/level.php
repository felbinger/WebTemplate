<?php
  class Level {
    private $id;
    private $name;

    /*  Constructor  */
    private function __construct($id, $name) {
      $this->id = $id;
      $this->name = $name;
    }

    /*  Getter  */
    public function getId() {
      return $this->id;
    }
    public function getName() {
      return $this->name;
    }

    public function getInfos() {
      return array("id" => $this->getId(),
                   "name" => $this->getName());
    }

    public function delete() {
      $GLOBALS['db']->update("DELETE FROM level WHERE id = :id", array("id" => $this->getId()));
    }

    public function update($name) {
      $GLOBALS['db']->update("UPDATE level SET name = :name WHERE id = :id", array("id" => $this->getId(), "name" => $name));
      $this->name = $name;
    }

    public static function create($name) {
      $id = $GLOBALS['db']->update("INSERT INTO level(`name`) VALUES(:name)", array("name" => $name));
      return self::getById($id);
    }

    public static function existById($id) {
      return is_array($GLOBALS['db']->query("SELECT `id` FROM level WHERE id = :id", array("id" => $id)));
    }

    public static function getById($id) {
      if (self::existById($id)) {
        $arr = $GLOBALS['db']->query("SELECT * FROM level WHERE id = :id", array("id" => $id));
        return new Level($arr['id'], $arr['name']);
      }
    }

    public static function existByName($name) {
      return is_array($GLOBALS['db']->query("SELECT `name` FROM level WHERE name = :name", array("name" => $name)));
    }

    public static function getByName($name) {
      if (self::existById($id)) {
        $arr = $GLOBALS['db']->query("SELECT * FROM level WHERE name = :name", array("name" => $name));
        return new Level($arr['id'], $arr['name']);
      }
    }

    public static function getAll() {
      $arr = $GLOBALS['db']->queryAll("SELECT id FROM `level`", array());
      $data = array();
      foreach ($arr as $row) {
        $data[] = self::getById($row['id'])->getInfos();
      }
      return $data;
    }
  }
