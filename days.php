<?php

  /*

    events(eventid, title, info, start, end, location, maps, priority, repeatid)

    days(eventid, day, startTime, endTime)

    repeatid: day + month + year

    $stmt = $conn->prepare("SELECT * FROM users WHERE token=?;");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows > 0){
      $stmt->bind_result($userid, $token, $first, $last, $admin);
      $stmt->fetch();
    }

  */

  include "sql.php";

  $stmt = $conn->prepare('SELECT days.eventid, days.day, days.startTime, days.endTime, events.title, events.info, events.location, events.maps, events.priority FROM days, events WHERE days.eventid = events.eventid AND events.repeatid = "0";');
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($eventid, $day, $startTime, $endTime, $title, $info, $location, $maps, $priority);

  $days = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");

  while($stmt->fetch()){

    $startDay = strtotime($days[$day]);

    for ($i=0; $i < 6; $i++) {

      $start = strtotime(date("d.m.Y", $startDay)." ".$startTime);
      $end = strtotime(date("d.m.Y", $startDay)." ".$endTime);

      $repeatid = date("dmY", $startDay)."-".$eventid;

      $stmt2 = $conn->prepare("INSERT INTO events(title, info, start, end, location, maps, priority, repeatid) VALUES(?, ?, ?, ?, ?, ?, ?, ?);");
      $stmt2->bind_param("ssiissis", $title, $info, $start, $end, $location, $maps, $priority, $repeatid);
      $stmt2->execute();

      $startDay = strtotime("+1 Week", $startDay);

    }

  }


 ?>
