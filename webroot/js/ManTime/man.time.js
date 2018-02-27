$(function() {
    $( ".client" ).change();
});

$( "td" ).on('change','select.client',function(){
    modifyClient(this);
});

function modifyClient (that) {
    var val = $(that).val();
    var idc = val;
    var select = $(that).parent().parent().find('td.cel_projet').children();
    $( select ).find('option').each(function() {
        if ( $.inArray($( this ).val(), optionProjects[idc]) != -1 ) {
            $( this ).show();
        }else{
            $( this ).hide();
        }
    });
    if ($( select ).find('option[selected=selected]:visible').length ){
        $( select ).val($( select ).find('option[selected=selected]:visible').val());
    }else{
        $( select ).val(optionProjects[idc][0][0]);
    }
    $( ".project" ).change();
    var select2 = $( that ).parent().parent().find('td.cel_profil').children();
    $( select2 ).find('option').each(function() {
        if ( $.inArray($( this ).val(), optionProfils[idc]) != -1 ) {
            $( this ).show();
        }else{
            $( this ).hide();
        }
    });
    if ($( select2 ).find('option[selected=selected]:visible').length ){
        $( select2 ).val($( select2 ).find('option[selected=selected]:visible').val());
    }else{
        $( select2 ).val(optionProfils[idc][0][0]);
    }
}

$( "td" ).on('change','select.project',function(){
    modifyProject(this);
});

function modifyProject(that) {
    var val = $(that).val();
    var idp = val.split('.')[1];
    var select = $( that ).parent().parent().find('td.cel_activit').children();
    $( select ).find('option').each(function() {
        if ( $.inArray($( this ).val(), optionActivits[idp]) != -1 ) {
            $( this ).show();
        }else{
            $( this ).hide();
        }
    });
    if ($( select ).find('option[selected=selected]:visible').length ){
        $( select ).val($( select ).find('option[selected=selected]:visible').val());
    }else{
        $( select ).val(optionActivits[idp][0][0]);
    }
}

$( "td" ).on('click','button.remove',function(){
    delLine(this);
});

function delLine(that) {
    $(that).parent().parent().remove();
}

$( "td" ).on('click','button#add',function(){
    addLine(this);
});

function addLine(that) {
    alert('add');
}
