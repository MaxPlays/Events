<?php

  include "sql.php";

  session_start();

  if(isset($_POST["eventid"]) && isset($_SESSION["admin"]) && $_SESSION["admin"] == 1){

    $eventid = $_POST["eventid"];

    if(isset($_POST["repeatid"])){
      $eventid = explode("-", $_POST["repeatid"])[1];
      $repeatid = "%-".explode("-", $_POST["repeatid"])[1];

      $stmt = $conn->prepare("DELETE FROM events WHERE eventid = ? OR repeatid LIKE ?;");
      $stmt->bind_param("is", $eventid, $repeatid);
      $stmt->execute();

    }else{

      $stmt = $conn->prepare("UPDATE events SET deleted = 1 WHERE eventid = ?;");
      $stmt->bind_param("i", $eventid);
      $stmt->execute();

      $stmt = $conn->prepare("DELETE FROM events WHERE eventid=? AND repeatid = -1;");
      $stmt->bind_param("i", $eventid);
      $stmt->execute();

    }

  }else{
    echo("Error");
  }


 ?>
