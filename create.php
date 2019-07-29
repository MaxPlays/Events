<?php

  include "sql.php";

  session_start();

  if(isset($_POST["title"]) && isset($_POST["info"]) && isset($_POST["date-from"]) && isset($_POST["date-to"]) && isset($_POST["time-from"]) && isset($_POST["time-to"]) && isset($_POST["location"]) && isset($_POST["maps"]) && isset($_POST["priority"]) && isset($_SESSION["admin"]) && $_SESSION["admin"] == 1){

    $dateFrom = strtotime($_POST["date-from"]);
    $dateTo = strtotime($_POST["date-to"]);

    $title = $_POST["title"];
    $info = $_POST["info"];
    if($dateTo <= 0){
      $dateTo = NULL;
    }
    $time = $_POST["time-from"]." - ".$_POST["time-to"];
    $location = $_POST["location"];
    $maps = $_POST["maps"];
    $priority = $_POST["priority"];

    $stmt = $conn->prepare("INSERT INTO events(title, info, start, end, time, location, maps, priority) VALUES(?, ?, ?, ?, ?, ?, ?, ?);");
    $stmt->bind_param("ssiisssi", $title, $info, $dateFrom, $dateTo, $time, $location, $maps, $priority);
    $stmt->execute();

    header('Location: index.php');
    die();

  }else{
    echo("Error");
  }



 ?>
