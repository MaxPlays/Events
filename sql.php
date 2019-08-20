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
  $conn->query("CREATE TABLE IF NOT EXISTS events(eventid INT AUTO_INCREMENT PRIMARY KEY, title TEXT, info TEXT, start LONG, end LONG, location TEXT, maps TEXT, priority INT DEFAULT 0, repeatid VARCHAR(64) NOT NULL UNIQUE, deleted INT DEFAULT 0);");
  $conn->query("CREATE TABLE IF NOT EXISTS zusagen(userid INT, eventid INT, PRIMARY KEY(userid, eventid), FOREIGN KEY (eventid) REFERENCES events (eventid) ON DELETE CASCADE, FOREIGN KEY (userid) REFERENCES users (userid) ON DELETE CASCADE);");
  $conn->query("CREATE TABLE IF NOT EXISTS absagen(userid INT, eventid INT, PRIMARY KEY(userid, eventid), FOREIGN KEY (eventid) REFERENCES events (eventid) ON DELETE CASCADE, FOREIGN KEY (userid) REFERENCES users (userid) ON DELETE CASCADE);");
  $conn->query("CREATE TABLE IF NOT EXISTS days(eventid INT, day INT, startTime VARCHAR(5), endTime VARCHAR(5), PRIMARY KEY(eventid, day), FOREIGN KEY (eventid) REFERENCES events (eventid) ON DELETE CASCADE);");
 ?>
