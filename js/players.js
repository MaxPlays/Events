$('#players-modal').on('show.bs.modal', function (event) {
  refreshPlayers();
});

function refreshPlayers(){

  $("tbody").html("Lade...");

  $.post("players.php", {action: "list"}).done(function(data){

    $("tbody").html("");

    var obj = JSON.parse(data);

    for(var i = 0; i < obj.length; i++){
      $("tbody").append('<tr><td>' + obj[i].name + '</td><td><a href="#" class="player-token" data-token="' + obj[i].token + '">Show token</a></td><td><a class="btn btn-danger text-white remove-player-button" href="#" data-userid="' + obj[i].userid + '">Entfernen</a></td></tr>');
    }

  });

}

$(document).on("click", ".remove-player-button", function(event){

  $("tbody").html("Lade...");

  $.post("players.php", {action: "remove", userid: $(event.target).data("userid")}).done(function(data){
    if(data == "success"){
      refreshPlayers();
    }else{
      $("tbody").html("Ein Fehler ist aufgetreten.");
      setTimeout(function(){
        refreshPlayers();
      }, 3000);
    }
  });

});

$("#add-player-button").click(function(){

  var first = $("#add-player-first").val();
  var last = $("#add-player-last").val();

  $("#add-player-first").val("");
  $("#add-player-last").val("");

  $("tbody").html("Lade...");

  $.post("players.php", {action: "add", first: first, last: last}).done(function(data){
    if(data == "success"){
      refreshPlayers();
    }else{
      $("tbody").html("Ein Fehler ist aufgetreten.");
      setTimeout(function(){
        refreshPlayers();
      }, 3000);
    }
  });

});

$(document).on("click", ".player-token", function(event){

  var token = $(event.target).data("token");
  alert(token);

});
