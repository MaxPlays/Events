
$('#more-info-modal').on('show.bs.modal', function (event) {

  var button = $(event.relatedTarget);
  var repeatid = button.data("repeatid");

  if(repeatid == "-1"){
    $(".delete-repeat-events").hide();
  }else{
    $(".delete-repeat-events").show();
  }

});

$('#more-info-modal').on('hide.bs.modal', function (event) {

  $(".delete-options").slideUp();

});

$(".delete-button").click(function(){
  $(".delete-options").slideToggle();
});

$(".delete-event").click(function(){

  $(".delete-options").slideUp();
  $.post("delete.php", {eventid: $(".more-info-modal-eventid").html()}).done(function(){
    document.location.reload();
  });

});

$(".delete-repeat-events").click(function(){

  $(".delete-options").slideUp();
  $.post("delete.php", {eventid: $(".more-info-modal-eventid").html(), repeatid: $(".more-info-modal-repeatid").html()}).done(function(){
    document.location.reload();
  });

});
