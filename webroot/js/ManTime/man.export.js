$(function() {
    init();
});


function init(){
    initMultiple();

    $( ".datepicker" ).each(function() {
        // if ($( this ).attr('value').length > 10) {
        //     $( this ).attr('value', moment($( this ).attr('value'), "DD/MM/YYYY hh:mm").format("YYYY-MM-DD"));
        // }
        $( this ).attr('type', 'date');
    });

    $("#import_export").on("click", function(){
        var target = $(this).attr('data-target');
        document.location.replace('/temps/'+target);
    });

    $('#export_fitnet').on('click', function(e){
        $('form').attr('action','/exportFitnet/export');
        $('form').submit();
    })
}

function initMultiple(){
    $('.multiple option').mousedown(function(e) {
        e.preventDefault();
        $(this).prop('selected', !$(this).prop('selected'));
        return false;
    });

    $( "#search_client" ).on('keyup', function (e){
        var search_text = $(this).val().toLowerCase();
        $('select[name="client[]"] option').each(function(){
            if ($(this).text().toLowerCase().match('.*('+search_text+').*')) {
                $(this).show();
            }else{
                $(this).hide();
            }
        });
    });

    $( "#search_user" ).on('keyup', function (e){
        var search_text = $(this).val().toLowerCase();
        $('select[name="user[]"] option').each(function(){
            if ($(this).text().toLowerCase().match('.*('+search_text+').*')) {
                $(this).show();
            }else{
                $(this).hide();
            }
        });
    });

    $('.height-input').on('click', function(e){
        $(this).parent().prev().val('');
        $(this).parent().prev().keyup();
    });
}
