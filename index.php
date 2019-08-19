<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Cherokees Events</title>

    <meta name="author" content="Maximilian Negedly">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/master.css">
  </head>
  <body>

    <?php

    include "sql.php";

    $token = "";
    if(isset($_GET["token"])){
      $token = $_GET["token"];
      setcookie("token", $token, time() + (86400 * 30 * 6), "/");
    }elseif(isset($_COOKIE["token"])){
      $token = $_COOKIE["token"];
    }

    if(strlen($token) > 0){
      $stmt = $conn->prepare("SELECT * FROM users WHERE token=?;");
      $stmt->bind_param("s", $token);
      $stmt->execute();
      $stmt->store_result();
      if($stmt->num_rows > 0){
        $stmt->bind_result($userid, $token, $first, $last, $admin);
        $stmt->fetch();
        session_start();
        $_SESSION["userid"] = $userid;
        $_SESSION["admin"] = $admin;
      }else{
        unset($_COOKIE['token']);
        $first = "";
        $last = "";
        $userid = -1;
        $admin = 0;
      }
    }else{
      $first = "";
      $last = "";
      $userid = -1;
      $admin = 0;
    }

     ?>

    <div class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="navbar-brand">
        Cherokees Events
      </div>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
          </li>
          <?php
            if($admin == 1){

              echo('
              <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#create-modal">Erstellen</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#players-modal">SpielerInnen</a>
              </li>
              ');

            }
           ?>
        </ul>
      </div>
    </div>

    <div class="modal fade" id="more-info-modal" tabindex="-1" role="dialog" aria-labelledby="more-info-modal-title" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="more-info-modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p class="more-info-modal-info"></p>
            <h6>Datum und Uhrzeit</h6>
            <p>
            <span class="more-info-modal-date"></span><br><span class="more-info-modal-time"></span>
            </p>
            <h6>Ort</h6>
            <span class="more-info-modal-location"></span> <span class="more-info-modal-maps-wrapper">(<a class="more-info-modal-maps" target="_blank">Google Maps</a>)</span>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Fertig</button>
          </div>
        </div>
      </div>
    </div>



    <div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="create-modal-title" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="create-modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body create-modal-phase create-modal-phase-1">
              <hr>
              <div class="create-modal-single create-modal-click">
                <h5 class="mt-1">Einmaliges Event</h5>
                <small class="text-muted">Ein einmalig stattfindendes Event</small>
              </div>
              <hr>
              <div class="create-modal-repeat create-modal-click">
                <h5>Regelmäßiges Event</h5>
                <small class="text-muted">Ein sich regelmäßig wiederholendes Event</small>
              </div>
              <hr>
          </div>
          <div class="modal-body create-modal-phase create-modal-phase-2">
              <div class="form-group">
                <div class="form-group">
                  <input type="text" required class="form-control" id="create-title" name="title" placeholder="Titel">
                </div>
                <div class="form-group">
                  <textarea  rows="5" cols="80" class="form-control" id="create-info" name="info" placeholder="Beschreibung"></textarea>
                </div>
                <div class="form-group">
                  <h6>Priorität</h6>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" name="priority" id="priority">
                    <label class="form-check-label" for="priority">
                      Oben anzeigen
                    </label>
                  </div>
                </div>
              </div>
          </div>
          <div class="modal-body create-modal-phase create-modal-phase-3">
              <h4>Beginn</h4>
              <div class="form-group form-inline">
                <input type="date" class="form-control mr-1" name="date-from" id="create-date-from"> um <input type="time" class="form-control ml-1" name="time-from" id="create-time-from">
              </div>
              <h4>Ende</h4>
              <div class="form-group form-inline">
                <input type="date" class="form-control mr-1" name="date-to" id="create-date-to"> um <input type="time" class="form-control ml-1" name="time-to" id="create-time-to">
              </div>
              <div class="create-modal-repeat-wrapper">
                <h4>Wiederholen</h4>
                <div class="create-modal-days">
                  <div class="create-modal-day">Montag</div>
                  <div class="create-modal-day">Dienstag</div>
                  <div class="create-modal-day">Mittwoch</div>
                  <div class="create-modal-day">Donnerstag</div>
                  <div class="create-modal-day">Freitag</div>
                  <div class="create-modal-day">Samstag</div>
                  <div class="create-modal-day">Sonntag</div>
                </div>
              </div>
          </div>
          <div class="modal-body create-modal-phase create-modal-phase-4">
              <input type="text" class="form-control" id="create-location" name="location" placeholder="Name des Orts">
              <br>
              <input type="text" class="form-control" id="create-maps" name="maps" placeholder="Google Maps Link (optional)">
          </div>
          <div class="modal-footer create-modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
            <button type="button" class="btn btn-primary create-modal-continue">Weiter</button>
            <button type="button" class="btn btn-primary create-modal-create">Erstellen</button>
          </div>
        </div>
      </div>
    </div>

    <?php
/*
      if($admin == 1){

        echo('

        <div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="create-modal-title" aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="create-modal-title">Event erstellen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="create.php" method="post">
                  <div class="form-group">
                    <input type="text" required class="form-control" id="create-title" name="title" placeholder="Titel">
                  </div>
                  <div class="form-group">
                    <textarea  rows="5" cols="80" class="form-control" id="create-info" name="info" placeholder="Beschreibung"></textarea>
                  </div>
                  <div class="form-group">
                    <h6>Datum</h6>
                    <label>Von:</label>
                    <input type="date" required class="form-control" name="date-from" id="create-date-from">
                    <br>
                    <label>Bis (optional):</label>
                    <input type="date" class="form-control" name="date-to" id="create-date-to">
                  </div>
                  <div class="form-group">
                    <h6>Uhrzeit</h6>
                    <label>Von:</label>
                    <input type="time" class="form-control" name="time-from" id="create-time-from">
                    <br>
                    <label>Bis:</label>
                    <input type="time" class="form-control" name="time-to" id="create-time-to">
                  </div>
                  <div class="form-group">
                    <h6>Ort</h6>
                    <input type="text" class="form-control" id="create-location" name="location" placeholder="Ort">
                    <br>
                    <input type="text" class="form-control" id="create-maps" name="maps" placeholder="Google Maps Link (optional)">
                  </div>
                  <div class="form-group">
                    <h6>Priorität</h6>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" name="priority" id="priority">
                      <label class="form-check-label" for="priority">
                        Oben anzeigen
                      </label>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary">Erstellen</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        ');

      }
*/


     ?>

    <div class="modal fade" id="anmelden-modal" tabindex="-1" role="dialog" aria-labelledby="anmelden-modal-title" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">An-/abmelden</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body anmelden-modal-body">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" id="button-anmelden">Anmelden</button>
            <button type="button" class="btn btn-danger" id="button-abmelden">Abmelden</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="players-modal" tabindex="-1" role="dialog" aria-labelledby="players-modal-title" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Spielerinnen und Spieler</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table">
              <thead>
                <tr>
                  <td>
                    <input type="text" class="form-control" placeholder="Vorname" id="add-player-first">
                  </td>
                  <td>
                    <input type="text" class="form-control" placeholder="Nachname" maxlength="3" id="add-player-last">
                  </td>
                  <td>
                    <a class="btn btn-primary text-white" id="add-player-button" href="#">Hinzufügen</a>
                  </td>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>

    <div class="userid"><?php

      if($userid >= 0){
        echo($userid);
      }

    ?></div>


    <div class="jumbotron">
      <h1 class="display-4">Willkommen<?php

        if(strlen($first) > 0){
          echo(", ".$first);
        }

      ?>!</h1>
      <p class="lead">Auf dieser Website siehst du alle anstehenden Termine, die die Vienna Cherokees betreffen.</p>
      <hr class="my-4">

      <?php

        if($userid >= 0){

          echo('

          <p>Du bist eingeloggt und kannst dich nun zu Trainings, Teambuildings und anderen Aktivitäten anmelden. (<a href="#" id="login">Falscher Account?</a>)</p>
          <div id="login-form">
          <div class="input-group mb-3">
          <input type="text" placeholder="Dein Zugriffscode" class="form-control" id="token-input">
          <div class="input-group-append">
          <a class="btn btn-primary input-group-button text-white" id="token-input-button" href="#">Einloggen</a>
          </div>
          </div>
          <span id="login-message"></span>
          </div>

          ');

        }else{

          echo('

          <p>Nutze deinen persönlichen Zugriffscode, um dich zu Trainings, Teambuildings und anderen Aktivitäten anzumelden.</p>
          <p>
          <div class="input-group mb-3">
          <input type="text" placeholder="Dein Zugriffscode" class="form-control" id="token-input">
          <div class="input-group-append">
          <a class="btn btn-primary input-group-button text-white" id="token-input-button" href="#">Einloggen</a>
          </div>
          </div>
          <span id="login-message"></span>
          </p>

          ');

        }

       ?>

    </div>

    <h4 class="ml-4 important-events"><small class="text-muted">Wichtige Events</small></h4>
    <hr class="mt-1 ml-4 mr-4 important-events">

    <div class="row ml-1 mr-1 important-events" id="important">

      <?php

      $time = time() - (60 * 60 * 24);

      $stmt = $conn->prepare("DELETE FROM events WHERE start < ?;");
      $stmt->bind_param("i", $time);
      $stmt->execute();



      $stmt = $conn->prepare("SELECT * FROM events WHERE priority = 1 ORDER BY start ASC LIMIT 3;");
         $stmt->execute();
         $stmt->store_result();
         if($stmt->num_rows > 0){
           $stmt->bind_result($eventid, $title, $info, $dateFrom, $dateTo, $time, $location, $maps, $priority);

           while($stmt->fetch()){
             $dayFrom = date("j", $dateFrom);
             $monthFrom = date("n", $dateFrom);
             $yearFrom = date("Y", $dateFrom);
             $monthFromName = date("M", $dateFrom);

             if($dateTo != NULL){
              $dayTo = date("j", $dateTo);
              $monthTo = date("n", $dateTo);
              $yearTo = date("Y", $dateTo);
              $monthToName = date("M", $dateTo);

             echo('

             <div class="col-sm-4">
             <div class="card bg-dark text-white">
               <div class="card-body">
                 <div class="card-title-wrapper">
                   <h2 class="card-title"><span class="date-month">'.$monthFromName.'</span><span class="date-day">'.$dayFrom.'</span></h2>
                   <h2 class="card-title-middle">bis</h2>
                   <h2 class="card-title"><span class="date-month">'.$monthToName.'</span><span class="date-day">'.$dayTo.'</span></h2>
                 </div>
                 <p class="card-text">'.$title.'</p>
                 <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#more-info-modal" data-title="'.$title.'" data-date="'.$dayFrom.'.'.$monthFrom.'.'.$yearFrom.' - '.$dayTo.'.'.$monthTo.'.'.$yearTo.'" data-time="'.$time.'" data-info="'.$info.'" data-location="'.$location.'" data-maps="'.$maps.'">Mehr Info</a>
                 <a href="#" class="btn btn-secondary" data-toggle="modal" data-target="#anmelden-modal" data-eventid="'.$eventid.'">An-/abmelden</a>
               </div>
             </div>
           </div>

             ');

           }else{

             echo('

             <div class="col-sm-4">
             <div class="card bg-dark text-white">
               <div class="card-body">
                 <div class="card-title-wrapper">
                   <h2 class="card-title"><span class="date-month">'.$monthFromName.'</span><span class="date-day">'.$dayFrom.'</span></h2>
                 </div>
                 <p class="card-text">'.$title.'</p>
                 <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#more-info-modal" data-title="'.$title.'" data-date="'.$dayFrom.'.'.$monthFrom.'.'.$yearFrom.'" data-time="'.$time.'" data-info="'.$info.'" data-location="'.$location.'" data-maps="'.$maps.'">Mehr Info</a>
                 <a href="#" class="btn btn-secondary" data-toggle="modal" data-target="#anmelden-modal" data-eventid="'.$eventid.'">An-/abmelden</a>
               </div>
             </div>
             </div>

             ');

           }
           }

        }

        ?>
      </div>

      <h4 class="ml-4 mt-3 important-events"><small class="text-muted">Alle Events</small></h4>
      <hr class="mt-1 ml-4 mr-4 important-events">

      <div class="row ml-1 mr-1">
        <?php

      $stmt = $conn->prepare("SELECT * FROM events WHERE priority = 0 ORDER BY start ASC LIMIT 6;");
         $stmt->execute();
         $stmt->store_result();
         if($stmt->num_rows > 0){
           $stmt->bind_result($eventid, $title, $info, $dateFrom, $dateTo, $time, $location, $maps, $priority);

           while($stmt->fetch()){
             $dayFrom = date("j", $dateFrom);
             $monthFrom = date("n", $dateFrom);
             $yearFrom = date("Y", $dateFrom);
             $monthFromName = date("M", $dateFrom);

             if($dateTo != NULL){
              $dayTo = date("j", $dateTo);
              $monthTo = date("n", $dateTo);
              $yearTo = date("Y", $dateTo);
              $monthToName = date("M", $dateTo);

             echo('

             <div class="col-sm-4">
             <div class="card bg-dark text-white">
               <div class="card-body">
                 <div class="card-title-wrapper">
                   <h2 class="card-title"><span class="date-month">'.$monthFromName.'</span><span class="date-day">'.$dayFrom.'</span></h2>
                   <h2 class="card-title-middle">bis</h2>
                   <h2 class="card-title"><span class="date-month">'.$monthToName.'</span><span class="date-day">'.$dayTo.'</span></h2>
                 </div>
                 <p class="card-text">'.$title.'</p>
                 <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#more-info-modal" data-title="'.$title.'" data-date="'.$dayFrom.'.'.$monthFrom.'.'.$yearFrom.' - '.$dayTo.'.'.$monthTo.'.'.$yearTo.'" data-time="'.$time.'" data-info="'.$info.'" data-location="'.$location.'" data-maps="'.$maps.'">Mehr Info</a>
                 <a href="#" class="btn btn-secondary" data-toggle="modal" data-target="#anmelden-modal" data-eventid="'.$eventid.'">An-/abmelden</a>
               </div>
             </div>
           </div>

             ');

           }else{

             echo('

             <div class="col-sm-4">
             <div class="card bg-dark text-white">
               <div class="card-body">
                 <div class="card-title-wrapper">
                   <h2 class="card-title"><span class="date-month">'.$monthFromName.'</span><span class="date-day">'.$dayFrom.'</span></h2>
                 </div>
                 <p class="card-text">'.$title.'</p>
                 <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#more-info-modal" data-title="'.$title.'" data-date="'.$dayFrom.'.'.$monthFrom.'.'.$yearFrom.'" data-time="'.$time.'" data-info="'.$info.'" data-location="'.$location.'" data-maps="'.$maps.'">Mehr Info</a>
                 <a href="#" class="btn btn-secondary" data-toggle="modal" data-target="#anmelden-modal" data-eventid="'.$eventid.'">An-/abmelden</a>
               </div>
             </div>
             </div>

             ');

           }
           }

        }

       ?>
</div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="js/info.js" charset="utf-8"></script>
    <script src="js/players.js" charset="utf-8"></script>
    <script src="js/login.js" charset="utf-8"></script>
    <script src="js/create.js" charset="utf-8"></script>

  </body>
</html>
