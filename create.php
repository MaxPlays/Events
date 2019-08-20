<?php

  include "sql.php";

  session_start();

  if(isset($_POST["title"]) && isset($_POST["info"]) && isset($_POST["start"]) && isset($_POST["end"]) && isset($_POST["priority"]) && isset($_POST["repeat"]) && isset($_POST["location"]) && isset($_POST["maps"]) && isset($_SESSION["admin"]) && $_SESSION["admin"] == 1){


    $title = $_POST["title"];
    $info = $_POST["info"];

    $start = $_POST["start"];
    $end = $_POST["end"];

    $location = $_POST["location"];
    $maps = $_POST["maps"];

    if($_POST["priority"] == 1){
      $priority = 1;
    }else{
      $priority = 0;
    }

    if($start > 0 && $end > 0){
      $repeatid = "-1";

      $stmt = $conn->prepare("INSERT INTO events(title, info, start, end, location, maps, priority, repeatid) VALUES(?, ?, ?, ?, ?, ?, ?, ?);");
      $stmt->bind_param("ssiissis", $title, $info, $start, $end, $location, $maps, $priority, $repeatid);
      $stmt->execute();
    }else{
      $repeatid = "0";

      $stmt = $conn->prepare("INSERT INTO events(title, info, start, end, location, maps, priority, repeatid) VALUES(?, ?, ?, ?, ?, ?, ?, ?);");
      $stmt->bind_param("ssiissis", $title, $info, $start, $end, $location, $maps, $priority, $repeatid);
      $stmt->execute();

      $eventid = $conn->insert_id;

      $days = array("Montag" => 0, "Dienstag" => 1, "Mittwoch" => 2, "Donnerstag" => 3, "Freitag" => 4, "Samstag" => 5, "Sonntag" => 6);


      $repeat = json_decode($_POST["repeat"], true);
      $stmt = $conn->prepare("INSERT INTO days(eventid, day, startTime, endTime) VALUES(?, ?, ?, ?);");

      $startTime = $repeat["startTime"];
      $endTime = $repeat["endTime"];

      foreach ($repeat["days"] as $day) {
        $id = $days[$day["day"]];
        $stmt->bind_param("iiss", $eventid, $id, $startTime, $endTime);
        $stmt->execute();
      }

      include "days.php";

    }

  }else{
    echo("Error");
  }



 ?>
