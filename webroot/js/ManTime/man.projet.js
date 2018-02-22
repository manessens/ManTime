$(function() {
    $( ".datepicker" ).each(function() {
        $( this ).attr('value', moment($( this ).attr('value'), "DD/MM/YYYY hh:mm").format("YYYY-MM-DD"));
        $( this ).attr('type', 'date');
    });
});
