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

$( "#search_participant" ).on('keyup', function (e){
    var search_text = $(this).val();
    $('select[name="participant[]"] option').each(function(){
        if ($(this).text().match('.*('+search_text+').*')) {
            $(this).show();
        }else{
            $(this).hide();
        }
    });
});

$( "#search_activit" ).on('keyup', function (e){
    var search_text = $(this).val();
    $('select[name="activities[]"] option').each(function(){
        if ($(this).text().match('.*('+search_text+').*')) {
            $(this).show();
        }else{
            $(this).hide();
        }
    });
});
