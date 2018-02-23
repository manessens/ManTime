$(function() {
    $( ".datepicker" ).each(function() {
        if ($( this ).attr('value').length > 10) {
            $( this ).attr('value', moment($( this ).attr('value'), "DD/MM/YYYY hh:mm").format("YYYY-MM-DD"));
        }
        $( this ).attr('type', 'date');
    });
});

$('.multiple option').mousedown(function(e) {
    e.preventDefault();
    $(this).prop('selected', !$(this).prop('selected'));
    return false;
});

$( "form" ).submit(function( event ) {
    $( ".datepicker" ).each(function() {
        $( this ).attr('type', 'text');
        if ($( this ).val().length = 10) {
            $( this ).val( moment($( this ).val(), "YYYY-MM-DD").format("DD/MM/YYYY hh:mm"));
        }
    });
  event.preventDefault();
  return true;
});
