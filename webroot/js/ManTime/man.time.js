$(function() {
    $( ".client" ).change();
    $( ".project" ).change();
});

$( ".client" ).change(function (e) {
    var val = $(this).val();
    var idc = val;
    var select = $(this).parent().parent().find('td.cel_projet').children();
    $( select ).find('option').each(function() {
        if ( $.inArray($( this ).val(), optionProjects[idc]) != -1 ) {
            $( this ).show();
            if ($( select ).find('option[selected=selected]:visible').length ){
                $( select ).val($( select ).find('option[selected=selected]:visible').val());
            }else{
                $( select ).val(optionProjects[idc][0]);
            }
        }else{
            $( this ).hide();
        }
    });
    var select2 = $( this ).parent().parent().find('td.cel_profil').children();
    $( select2 ).find('option').each(function() {
        if ( $.inArray($( this ).val(), optionProfils[idc]) != -1 ) {
            $( this ).show();
            if ($( select ).find('option[selected=selected]:visible').length ){
                $( select ).val($( select ).find('option[selected=selected]:visible').val());
            }else{
                $( select ).val(optionProfils[idc][0]);
            }
        }else{
            $( this ).hide();
        }
    });
})

$( ".project" ).change(function (e) {
    var val = $(this).val();
    var idp = val.split('.')[1];
    var select = $( this ).parent().parent().find('td.cel_activit').children();
    $( select ).find('option').each(function() {
        if ( $.inArray($( this ).val(), optionActivits[idp]) != -1 ) {
            $( this ).show();
            if ($( select ).find('option[selected=selected]:visible').length ){
                $( select ).val($( select ).find('option[selected=selected]:visible').val());
            }else{
                $( select ).val(optionActivits[idp][0]);
            }
        }else{
            $( this ).hide();
        }
    });
})
