$(function() {
    $( ".datepicker" ).each(function() {
        if ($( this ).attr('value').length > 10) {
            $( this ).attr('value', moment($( this ).attr('value'), "DD/MM/YYYY hh:mm").format("YYYY-MM-DD"));
        }
        $( this ).attr('type', 'date');
    });
});