<?php
  class Database {
    private $conn;

    /*  Constructor  */
    public function __construct($typ, $hostname, $port, $charset, $username, $password, $database) {
      try {
        $this->conn = new PDO($typ.':host='.$hostname.';port='.$port.';dbname='.$database.';charset='.$charset, $username, $password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      } catch (PDOEXxecption $e) {
        die(json_encode(array("error" => 'database error!')));
      }
    }

    /*  Function for database SELECTS, to get one row  */
    public function query($sql, $data) {
      $this->conn->beginTransaction();
      $stmt = $this->conn->prepare($sql);
      $stmt->execute($data);
      $this->conn->rollBack();
  		return $stmt->fetch();
    }

    /*  Function for database SELECTS, to get all rows  */
    public function queryAll($sql, $data) {
      $this->conn->beginTransaction();
      $stmt = $this->conn->prepare($sql);
      $stmt->execute($data);
      $this->conn->rollBack();
  		return $stmt->fetchAll();
    }

    /*  Function for database INSERT, UPDATE and DELETE  */
    public function update($sql, $data) {
      $stmt = $this->conn->prepare($sql);
      $stmt->execute($data);
  		return $this->conn->lastInsertId();
    }
  }
?>
