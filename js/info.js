$('#more-info-modal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  var title = button.data('title');
  var date = button.data('date');
  var time = button.data('time');
  var info = button.data('info');
  var location = button.data('location');
  var maps = button.data('maps');
  var modal = $(this);
  modal.find('.modal-title').text(title);
  modal.find('.more-info-modal-info').text(info);
  modal.find('.more-info-modal-date').text(date);
  modal.find('.more-info-modal-time').text(time);
  modal.find('.more-info-modal-location').text(location);
  modal.find('.more-info-modal-maps').prop("href", maps);
  if(maps.length > 0){
    modal.find('.more-info-modal-maps-wrapper').show();
  }else{
    modal.find('.more-info-modal-maps-wrapper').hide();
  }
});

  var event = 0;

  function setEvent(id){
    event = id;
  }

$('#anmelden-modal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  setEvent(button.data('eventid'));
  refresh();
});

$("#button-anmelden").click(function(){

  if($(".userid").html().length > 0){
    $.post("anmelden.php", {eventid: event}).done(function(data){
      refresh();
    });
  }else{
    alert("Du bist nicht angemeldet. Bitte benutze deinen persönlichen Zugriffscode.");
  }
});

$("#button-abmelden").click(function(){

  if($(".userid").html().length > 0){
    $.post("abmelden.php", {eventid: event}).done(function(data){
      refresh();
    });
  }else{
    alert("Du bist nicht angemeldet. Bitte benutze deinen persönlichen Zugriffscode.");
  }

});

function refresh(){
  $(".anmelden-modal-body").html("");
  $.post("info.php", {eventid: event}).done(function(data){
    var obj = JSON.parse(data);
    var result = "";
    for(var i = 0; i < obj.zusagen.length; i++){
      result = result + '<div class="name bg-success text-white">' + obj.zusagen[i] + '</div>';
    }
    for(var i = 0; i < obj.absagen.length; i++){
      result = result + '<div class="name bg-danger text-white">' + obj.absagen[i] + '</div>';
    }
    for(var i = 0; i < obj.rest.length; i++){
      result = result + '<div class="name bg-secondary text-white">' + obj.rest[i] + '</div>';
    }
    $(".anmelden-modal-body").html(result);
  });
}

$("#token-input-button").click(function(){

  var token = $("#token-input").val();
  $(".input-group").hide();
  $("#login-message").html("Logge ein...");

  $.post("token.php", {token: token}).done(function(data){
    if(data == "success"){
      $("#login-message").html("Erfolgreich eingeloggt. Du wirst in 3 Sekunden weitergeleitet.");
      setTimeout(function(){
        location.reload();
      }, 3000);
    }else if(data == "error"){
      $(".input-group").show();
      $("#login-message").html("Der eingegebene Code ist ungültig.");
    }
  });

});

$("#login").click(function(){
  $("#login-form").toggle();
});
