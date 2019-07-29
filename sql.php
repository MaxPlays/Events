<?php
  $host = "127.0.0.1";
  $port = 3306;
  $user = "root";
  $password = "rootpw";
  $database = "events";
  $conn = new mysqli($host, $user, $password, $database);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  $conn->query("CREATE TABLE IF NOT EXISTS users(userid INT AUTO_INCREMENT PRIMARY KEY, token VARCHAR(36), first TEXT, last VARCHAR(3), admin INT DEFAULT 0);");
  $conn->query("CREATE TABLE IF NOT EXISTS events(eventid INT AUTO_INCREMENT PRIMARY KEY, title TEXT, info TEXT, start LONG, end LONG, time TEXT, location TEXT, maps TEXT, priority INT DEFAULT 0);");
  $conn->query("CREATE TABLE IF NOT EXISTS zusagen(userid INT, eventid INT, PRIMARY KEY(userid, eventid));");
  $conn->query("CREATE TABLE IF NOT EXISTS absagen(userid INT, eventid INT, PRIMARY KEY(userid, eventid));");
 ?>
