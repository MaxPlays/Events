$('#players-modal').on('show.bs.modal', function (event) {
  refreshPlayers();
});

function refreshPlayers(){

  $("tbody").html("Lade...");

  $.post("players.php", {action: "list"}).done(function(data){

    $("tbody").html("");

    var obj = JSON.parse(data);

    for(var i = 0; i < obj.length; i++){
      $("tbody").append('<tr><td>' + obj[i].name + '</td><td><a href="#" class="player-token" data-token="' + obj[i].token + '">Code kopieren</a></td><td><a class="btn btn-danger text-white remove-player-button" href="#" data-userid="' + obj[i].userid + '">Entfernen</a></td></tr>');
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

  var range = document.createRange(),
      selection;

  if (window.clipboardData) {
    window.clipboardData.setData("Text", token);
  } else {
    var tmpElem = $('<div>');
    tmpElem.css({
      position: "absolute",
      left: "-1000px",
      top: "-1000px",
    });

    tmpElem.text(token);
    $("body").append(tmpElem);

    range.selectNodeContents(tmpElem.get(0));
    selection = window.getSelection();
    selection.removeAllRanges();
    selection.addRange(range);

    try {
      document.execCommand("copy", false, null);
    }
    catch (e) {
      copyToClipboardFF(token);
    }

    tmpElem.remove();

    $(event.target).tooltip({
      trigger: 'click',
      placement: 'bottom'
    });

    $(event.target).tooltip('hide')
      .attr('data-original-title', "Kopiert!")
      .tooltip('show');

    setTimeout(function() {
      $(event.target).tooltip('hide');
    }, 1000);
}

});

function copyToClipboardFF(text) {
  window.prompt("Code kopieren", text);
}

function setTooltip(message) {
  $('button').tooltip('hide')
    .attr('data-original-title', message)
    .tooltip('show');
}

function hideTooltip() {
  setTimeout(function() {
    $('button').tooltip('hide');
  }, 1000);
}
