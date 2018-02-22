$(function() {
    $( ".datepicker" ).each(function() {
        if ($( this ).attr('value').length > 10) {
            $( this ).attr('value', moment($( this ).attr('value'), "DD/MM/YYYY hh:mm").format("YYYY-MM-DD"));
        }
        $( this ).attr('type', 'date');
    });
});

$( ".multiple option" ).mousedown(function() {
    var str = "<p>";
    $( ".multiple option:selected" ).each(function() {
      str += $( this ).text() + "</p><p> ";
    });
    str += "</p>";
    $( ".multiple option" ).parent().prev().prev('div.selected').text(str);
}).trigger( "mousedown" );

$('.multiple option').mousedown(function(e) {
    e.preventDefault();
    $(this).prop('selected', !$(this).prop('selected'));
    return false;
});
