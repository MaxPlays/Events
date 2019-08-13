<?php

  if(isset($_POST["eventid"])){

    include "sql.php";

    $eventid = $_POST["eventid"];

    $zusagen = array();
    $absagen = array();
    $rest = array();

    $done = array();

    $stmt = $conn->prepare("SELECT users.userid AS userid, users.first AS first, users.last AS last FROM users, zusagen WHERE zusagen.userid = users.userid AND zusagen.eventid = ? ORDER BY users.first ASC, users.last ASC;");
    $stmt->bind_param("i", $eventid);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows > 0){
      $stmt->bind_result($userid, $first, $last);
      while($stmt->fetch()){
        array_push($zusagen, $first." ".$last);
        array_push($done, $userid);
      }
    }

    $stmt = $conn->prepare("SELECT users.userid AS userid, users.first AS first, users.last AS last FROM users, absagen WHERE absagen.userid = users.userid AND absagen.eventid = ? ODER BY users.first ASC, users.last ASC;");
    $stmt->bind_param("i", $eventid);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows > 0){
      $stmt->bind_result($userid, $first, $last);
      while($stmt->fetch()){
        array_push($absagen, $first." ".$last);
        array_push($done, $userid);
      }
    }

    $stmt = $conn->prepare("SELECT userid, first, last FROM users ORDER BY first ASC, last ASC;");
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows > 0){
      $stmt->bind_result($userid, $first, $last);
      while($stmt->fetch()){
        if(in_array($userid, $done)){
          continue;
        }else{
          array_push($rest, $first." ".$last);
        }
      }
    }

    $result = json_encode(array("zusagen" => $zusagen, "absagen" => $absagen, "rest" => $rest));
    echo($result);


  }else{
    echo("Error");
  }



 ?>
