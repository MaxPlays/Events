var phase = 0;

var phases = {
  0: "",
  1: "Art auswählen",
  2: "Allgemeine Informationen",
  3: "Datum und Uhrzeit",
  4: "Weitere Informationen"
};

var options = {};

function clearOptions(){
  options.title = "";
  options.info = "";
  options.start = 0;
  options.end = 0;
  options.repeat = "";
  options.location = "";
  options.maps = "";
  options.priority = false;

  $("#create-title").removeClass("is-invalid");
  $("#create-date-from").removeClass("is-invalid");
  $("#create-date-to").removeClass("is-invalid");
  $(".create-modal-repeat-wrapper").hide();

  $("#create-title").val("");
  $("#create-info").val("");
  document.getElementById("priority").checked = false;
  $("#create-date-from").val("");
  $("#create-date-to").val("");
  $("#create-time-from").val("");
  $("#create-time-to").val("");
  $(".create-modal-day").removeClass("create-modal-day-selected");
  $("#create-location").val("");
  $("#create-maps").val("");
}

$(".create-modal-phase").hide();
clearOptions();

function progress(phase){
  this.phase = phase;

  $("#create-modal-title").html(phases[phase]);
  $(".create-modal-phase").hide();
  $(".create-modal-phase-" + phase).show();

  if(phase > 1){
    $(".create-modal-footer").show();
  }else{
    $(".create-modal-footer").hide();
  }

  if(phase == 4){
    $(".create-modal-create").show();
    $(".create-modal-continue").hide();
  }else{
    $(".create-modal-create").hide();
    $(".create-modal-continue").show();
  }

}

$('#create-modal').on('show.bs.modal', function (event) {

  progress(1);

});

$('#create-modal').on('hide.bs.modal', function (event) {

  progress(0);
  clearOptions();

});

$(document).on("click", ".create-modal-single", function(){
  progress(2);
});

$(document).on("click", ".create-modal-repeat", function(){
  $(".create-modal-repeat-wrapper").show();
  progress(2);
});

$(".create-modal-continue").click(function(){
  switch(phase){
    case 2:
      if($("#create-title").val().length == 0){
        $("#create-title").addClass("is-invalid");
      }else{
        options.title = $("#create-title").val();
        options.info = $("#create-info").val();
        options.priority = document.getElementById("priority").checked;
        progress(3);
      }
      break;
    case 3:
      if($("#create-date-from").val().length == 0 | $("#create-date-to").val().length == 0){
        $("#create-date-from").addClass("is-invalid");
        $("#create-date-to").addClass("is-invalid");
      }else{
        var from = new Date($("#create-date-from").val());
        if($("#create-time-from").val().length > 0){
          from.setHours($("#create-time-from").val().split(":")[0]);
          from.setMinutes($("#create-time-from").val().split(":")[1]);
        }
        var to = new Date($("#create-date-to").val());
        if($("#create-time-to").val().length > 0){
          to.setHours($("#create-time-to").val().split(":")[0]);
          to.setMinutes($("#create-time-to").val().split(":")[1]);
        }
        options.start = from.getTime();
        options.end = to.getTime();

        if($(".create-modal-day-selected").length > 0){
          var s = "[";
          var days = document.getElementsByClassName("create-modal-day-selected");
          for(var i = 0; i < days.length; i++){
            if(i != (days.length - 1)){
              s = s + '{"day":"' + days[i].innerHTML + '"},';
            }else{
              s = s + '{"day":"' + days[i].innerHTML + '"}';
            }
          }
          s = s + "]";
          options.repeat = s;
        }

        progress(4);
      }
      break;
  }
});

$(".create-modal-create").click(function(){
  options.location = $("#create-location").val();
  options.maps = $("#create-maps").val();

  console.log(options);
  $.post("create.php", options);
  document.location.reload();
});

$(".create-modal-day").click(function(event){
  $(event.target).toggleClass("create-modal-day-selected");
});

$("#create-date-from").on("input", function(){
  if($("#create-date-to").val().length == 0){
    $("#create-date-to").val($("#create-date-from").val());
  }
});
