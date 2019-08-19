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

if($("#important").html().replace(" ", "").length > 0){
  $(".important-events").show();
}
