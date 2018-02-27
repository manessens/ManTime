$(function() {
    $( ".client" ).change();
});

$( ".client" ).change(function(){
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
        $( select ).val(optionProjects[idc][0]);
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
        $( select2 ).val(optionProfils[idc][0]);
    }
}

$( ".project" ).change(function(){
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
        $( select ).val(optionActivits[idp][0]);
    }
}

$( ".remove" ).click(function(){
    delLine(this);
});

function delLine(that) {
    $(that).parent().parent().remove();
}

$( "#add" ).click(function(){
    addLine(this);
});

function addLine(that) {
    var id = $('#semainier>tbody tr:last').prev().attr('id');
    if (id == undefined) {
        id = -1;
    }
    id = Number(id)+1;
    var tr = $('<tr>', {
        id: id
    });
    var tdButton = $('<td>',{
        class:'action',
        scope:'col'
    });
    var button = $('<button>',{
        type:'button',
        class: "btn btn-danger remove",
        text: '-'
    })
    button.click(function(){
        delLine(this);
    });
    tdButton.append(button);
    tr.append(tdButton);

    var tdClient = $('<td>',{
        class:'cel_client',
        scope:'col'
    });
    var selectClient = $('<select>',{
        class:'client',
        name:'client['+id+']'
    })
    valueClients.forEach(function(val) {
        var option = $('<option>',{
            value:val,
            text:valueClients[val]
        })
        selectClient.append(option);
    });
    tdClient.append(selectClient);
    tr.append(tdClient);

    var tdProjet = $('<td>',{
        class:'cel_projet',
        scope:'col'
    });
    var selectProjet = $('<select>',{
        class:'project',
        name:'projet['+id+']'
    })
    valueProjects.forEach(function(val) {
        var option = $('<option>',{
            value:val,
            text:valueProjects[val]
        })
        selectProjet.append(option);
    });
    tdProjet.append(selectProjet);
    tr.append(tdProjet);

    if (id == 0) {
        tr.insertBefore('#total');
    }else{
        tr.insertAfter('#'+(id-1));
    }
}
