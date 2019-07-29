<?php

  if(isset($_POST["token"])){

    include "sql.php";

    $token = $_POST["token"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE token=?;");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows > 0){
      echo("success");
      setcookie("token", $token, time() + (86400 * 30 * 6), "/");
    }else{
      echo("error");
    }

  }else{
    echo("error");
  }


 ?>
