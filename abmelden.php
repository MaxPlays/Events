<?php

  session_start();

  if(isset($_POST["eventid"]) && isset($_SESSION["userid"])){

    include "sql.php";

    $userid = $_SESSION["userid"];
    $eventid = $_POST["eventid"];

    $stmt = $conn->prepare("DELETE FROM zusagen WHERE userid=? AND eventid=?;");
    $stmt->bind_param("ii", $userid, $eventid);
    $stmt->execute();

    $stmt = $conn->prepare("INSERT INTO absagen VALUES(?, ?);");
    $stmt->bind_param("ii", $userid, $eventid);
    $stmt->execute();

  }


 ?>
