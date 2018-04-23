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
    var st = this.scrollTop;
    e.target.selected = !e.target.selected;
    setTimeout(() => this.scrollTop = st, 0);
    this.focus();
});

$('.multiple option').onmousemove(function(e) {
    e.preventDefault();
});

$( "#search_participant" ).on('keyup', function (e){
    var search_text = $(this).val().toLowerCase();
    $('select[name="participant[]"] option').each(function(){
        if ($(this).text().toLowerCase().match('.*('+search_text+').*')) {
            $(this).show();
        }else{
            $(this).hide();
        }
    });
});

$( "#search_activit" ).on('keyup', function (e){
    var search_text = $(this).val().toLowerCase();
    $('select[name="activities[]"] option').each(function(){
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
