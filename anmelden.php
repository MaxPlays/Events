<?php

  session_start();

  if(isset($_POST["eventid"]) && isset($_SESSION["userid"])){

    include "sql.php";

    $userid = $_SESSION["userid"];
    $eventid = $_POST["eventid"];

    $stmt = $conn->prepare("DELETE FROM absagen WHERE userid=? AND eventid=?;");
    $stmt->bind_param("ii", $userid, $eventid);
    $stmt->execute();

    $stmt = $conn->prepare("INSERT INTO zusagen VALUES(?, ?);");
    $stmt->bind_param("ii", $userid, $eventid);
    $stmt->execute();

  }


 ?>
