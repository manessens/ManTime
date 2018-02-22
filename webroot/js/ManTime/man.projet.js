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

$( ".multiple" ).change(function() {
    var str = "";
    $( ".multiple option:selected" ).each(function() {
      str += $( this ).text() + ", ";
    });
    console.log($( this ).prev());
}).trigger( "change" );
