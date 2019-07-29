<?php

  session_start();

  if(isset($_POST["action"]) && isset($_SESSION["admin"]) && $_SESSION["admin"] == 1){

    include "sql.php";

    if($_POST["action"] == "list"){

      $result = array();

      $stmt = $conn->prepare("SELECT userid, first, last, token FROM users ORDER BY first;");
      $stmt->execute();
      $stmt->store_result();
      if($stmt->num_rows > 0){
        $stmt->bind_result($userid, $first, $last, $token);
        while($stmt->fetch()){
          array_push($result, array("userid" => $userid, "name" => $first." ".$last, "token" => $token));
        }

        echo(json_encode($result));
      }else{
        echo("[]");
      }

    }else if($_POST["action"] == "remove" && isset($_POST["userid"])){
      $userid = $_POST["userid"];

      $stmt = $conn->prepare("DELETE FROM users WHERE userid = ?;");
      $stmt->bind_param("i", $userid);
      $stmt->execute();

      $stmt = $conn->prepare("DELETE FROM zusagen WHERE userid = ?;");
      $stmt->bind_param("i", $userid);
      $stmt->execute();

      $stmt = $conn->prepare("DELETE FROM absagen WHERE userid = ?;");
      $stmt->bind_param("i", $userid);
      $stmt->execute();

      echo("success");
    }else if($_POST["action"] == "add" && isset($_POST["first"]) && isset($_POST["last"])){

      $first = $_POST["first"];
      $last = $_POST["last"];

      if(strlen($first) > 0 && strlen($last) <= 3){

        $token = md5(uniqid(rand(), true));

        $stmt = $conn->prepare("INSERT INTO users(first, last, token) VALUES(?, ?, ?);");
        $stmt->bind_param("sss", $first, $last, $token);
        $stmt->execute();

        echo("success");
      }else{
        echo("Error");
      }

    }else{
      echo("Error");
    }

  }else{
    echo("Error");
  }


 ?>
