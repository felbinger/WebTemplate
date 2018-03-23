<?php
  /*  Create database object  */
  include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
  include_once $_SERVER['DOCUMENT_ROOT'].'/assets/php/classes/database.php';

  $db = new Database($database['type'],
                     $database['host'],
                     $database['port'],
                     $database['char'],
                     $database['user'],
                     $database['pass'],
                     $database['name']);

  /*  Database Initialation  */
  /*  Table: Config  */
  //$db->update("CREATE TABLE IF NOT EXISTS `config` (`name` varchar(100) NOT NULL, `value` varchar(100) NOT NULL, PRIMARY KEY (`name`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; INSERT INTO `config` (`name`, `value`) VALUES ('logging', 'true'),('registration', 'true'), ('session_validity', '180');");
  /*  Table: Level  */
  //$db->update("CREATE TABLE IF NOT EXISTS `level` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(100) NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `name` (`name`)) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4; INSERT INTO `level` (`id`, `name`) VALUES (0, 'Administrator'), (2, 'Supporter'), (1, 'User');");
  /*  Table: Status  */
  //$db->update("CREATE TABLE IF NOT EXISTS `status` (`id` int(10) NOT NULL AUTO_INCREMENT, `name` varchar(100) NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `name` (`name`)) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4; INSERT INTO `status` (`id`, `name`) VALUES (1, 'Active'), (2, 'Inactive');");
  /*  Table: User  */
  //$db->update("CREATE TABLE IF NOT EXISTS `user` (`id` int(10) NOT NULL AUTO_INCREMENT,`username` varchar(170) NOT NULL,`realname` varchar(170) DEFAULT NULL,`email` varchar(170) NOT NULL,`password` varchar(250) NOT NULL,`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,`lastlogin` timestamp NULL DEFAULT NULL,`level` int(10) NOT NULL,`status` int(1) NOT NULL,PRIMARY KEY (`id`),UNIQUE KEY `username` (`username`),KEY `level` (`level`),KEY `status` (`status`)) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;ALTER TABLE `user`ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`level`) REFERENCES `level` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,ADD CONSTRAINT `user_ibfk_2` FOREIGN KEY (`status`) REFERENCES `status` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;");
  /*  Table: Sessions  */
  //$db->update("CREATE TABLE IF NOT EXISTS `session` ( `id` int(10) NOT NULL AUTO_INCREMENT, `user` int(10) DEFAULT NULL, `token` text NOT NULL, `created` datetime NOT NULL, `expire` datetime NOT NULL, `broken` int(1) NOT NULL, PRIMARY KEY (`id`), KEY `username` (`user`)) ENGINE=InnoDB DEFAULT CHARSET=latin1; ALTER TABLE `session` ADD CONSTRAINT `session_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`);");
  /*  Table: Log  */
  //$db->update("CREATE TABLE IF NOT EXISTS `log` (`id` int(10) NOT NULL AUTO_INCREMENT,`ip` varchar(200) NOT NULL,`userAgent` varchar(200) NOT NULL,`api` varchar(200) NOT NULL,`status` varchar(200) NOT NULL, `user` int(10) NOT NULL,`timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`),KEY `username` (`user`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; ALTER TABLE `log` ADD CONSTRAINT `log_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;");

  /*  Get website specific settings from the database */
  $config = array();
  foreach ($db->queryAll("SELECT name, value FROM `config`", array()) as $row) {
    $config[$row["name"]] = (($row["value"] === "true") ? true : (($row["value"] === "false") ? false : $row["value"]));
  }

  /*  Include classes   */
  include_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/classes/session.php';
  include_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/classes/user.php';
  include_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/classes/log.php';
  include_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/classes/level.php';
  include_once $_SERVER['DOCUMENT_ROOT'] . '/assets/php/classes/status.php';

  /*  utilities   */
  function dieInfos($array) {
    die(json_encode($array));
  }
  function dieSuccessful() {
    die(json_encode(array("successful" => true)));
  }
  function dieError($error) {
    die(json_encode(array("error" => $error)));
  }
