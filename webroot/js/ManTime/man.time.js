$(function() {
    // var id = $('#semainier tbody tr').length-1;
    // var node = $('#'+(id-1)).clone();
    // node = node.html().replace(id-1, id);
    // $('#semainier>tbody:last').append(node);


});
// $( ".project" ).change(function () {
//     var val = $(this).val();
//     var idp = val.split('.')[0];
//     // $(this).parent().next().children().val(idp);
//     $(this).parent().next().children().find('option[value!='+idp+']').hide();
//     $(this).parent().next().children().find('option[value='+idp+']').show();
// }).change();

$( ".client" ).change(function () {
    var val = $(this).val();
    var idc = val;
    var select = $(this).parent().parent().find('td.cel_projet').children();
    $( select ).find('option').each(function() {
        if ( $.inArray($( this ).val(), optionProjects[idc]) != -1 ) {
            $( this ).show();
            $( select ).val(optionProjects[idc][0]);
        }else{
            $( this ).hide();
        }
    });
}).change();

$( ".client" ).change(function () {
    var val = $(this).val();
    var idc = val;
    var select = $( ".client" ).parent().parent().find('td.cel_profil').children();
    $( select ).find('option').each(function() {
        if ( $.inArray($( this ).val(), optionProfils[idc]) != -1 ) {
            $( this ).show();
            $( select ).val(optionProfils[idc][0]);
        }else{
            $( this ).hide();
        }
    });
}).change();
