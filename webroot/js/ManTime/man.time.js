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
    // Client
    var tdClient = $('<td>',{
        class:'cel_client',
        scope:'col'
    });
    var selectClient = $('<select>',{
        class:'client',
        name:'client['+id+']'
    })
    for(var key in valueClients){
        var option = $('<option>',{
            value:key,
            text:valueClients[key]
        })
        selectClient.append(option);
    }
    selectClient.change(function(){
        modifyClient(this);
    });
    tdClient.append(selectClient);
    tr.append(tdClient);
    // Projet
    var tdProjet = $('<td>',{
        class:'cel_projet',
        scope:'col'
    });
    var selectProjet = $('<select>',{
        class:'project',
        name:'projet['+id+']'
    })
    for(var key in valueProjects){
        var option = $('<option>',{
            value:key,
            text:valueProjects[key]
        })
        selectProjet.append(option);
    }
    selectProjet.change(function(){
        modifyProject(this);
    });
    tdProjet.append(selectProjet);
    tr.append(tdProjet);
    // Profil
    var tdProfil = $('<td>',{
        class:'cel_profil',
        scope:'col'
    });
    var selectProfil = $('<select>',{
        class:'profil',
        name:'profil['+id+']'
    })
    for(var key in valueProfils){
        var option = $('<option>',{
            value:key,
            text:valueProfils[key]
        })
        selectProfil.append(option);
    }
    tdProfil.append(selectProfil);
    tr.append(tdProfil);
    // Activité
    var tdActivit = $('<td>',{
        class:'cel_activit',
        scope:'col'
    });
    var selectActivit = $('<select>',{
        class:'profil',
        name:'profil['+id+']'
    })
    for(var key in valueActivits){
        var option = $('<option>',{
            value:key,
            text:valueActivits[key]
        })
        selectActivit.append(option);
    }
    tdActivit.append(selectActivit);
    tr.append(tdActivit);
    // Days
    // @TODO: les jours


    if (id == 0) {
        tr.insertBefore('#total');
    }else{
        tr.insertAfter('#'+(id-1));
    }

    $( ".client" ).change();
}
