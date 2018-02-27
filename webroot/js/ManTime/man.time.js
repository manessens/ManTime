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
    // $(this).parent().next().children().val(idp);
    var select = $(this).parent().next().children();
    console.log(optionProjects[idc]);
    // $(this).parent().next().children().find('option[value!='+idp+']').hide();
    // $(this).parent().next().children().find('option[value='+idp+']').show();
}).change();
