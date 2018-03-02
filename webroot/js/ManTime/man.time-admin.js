$(function() {
    $( ".users" ).change();
    alert = false;
    alertVerouillage = false;
    updateTotal();
});
var alertVerouillage;

$( "form" ).on('submit',function (e){
    if ($('#validat').prop('checked')) {
        var modal = new ModalWindow({
            Title: "Validation semaine",
            Message: "Vous avez coché la validation pour export, les consultants ne pourront plus modifier leur temps. Êtes-vous sûr de vouloir continuer ?",
            Buttons: [["btn-primary admin", 'Non', 'false'], ["btn-danger admin", 'Oui', 'true']],
            CallBack: function(result, event, formData, ExtraData, rootDiv) {
                if (result === 'false') {
                    $('#validat').prop('checked', false);
                    alertVerouillage = true;
                }else{
                    alertVerouillage = false;
                    $( "form" ).submit();
                }
            },
            Center: true,
            AllowClickAway: false
        });
        modal.Show();
    };
    if (alertVerouillage) {
        e.preventDefault();
    }
});

$( "#validat" ).click(function(){
    alertVerouillage = $('#validat').prop('checked');
});

$( ".users" ).change(function(){
    modifyUser(this);
});

function modifyUser (that) {
    var val = $(that).val();
    var idu = val;
    var select = $(that).parent().parent().find('td.cel_client').children();
    $( select ).find('option').each(function() {
        if ( $.inArray($( this ).val(), optionClients[idu]) != -1 ) {
            $( this ).show();
        }else{
            $( this ).hide();
        }
    });
    if ($( select ).find('option[selected=selected]:visible').length ){
        $( select ).val($( select ).find('option[selected=selected]:visible').val());
    }else{
        $( select ).val(optionClients[idu][0]);
    }
    $( ".client" ).change();
}


$( ".client" ).change(function(){
    modifyClient(this);
});

function modifyClient (that) {
    var val = $(that).val();
    var idc = val.split('.')[1];
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
    var idp = val.split('.')[2];
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
    // User
    var tdUser = $('<td>',{
        class:'cel_users',
        scope:'col'
    });
    var selectUser = $('<select>',{
        class:'user',
        name:'users['+id+']'
    })
    for(var key in valueUsers){
        var option = $('<option>',{
            value:key,
            text:valueUsers[key]
        })
        selectUser.append(option);
    }
    selectUser.change(function(){
        modifyUser(this);
    });
    tdUser.append(selectUser);
    tr.append(tdUser);
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
        class:'activit',
        name:'activities['+id+']'
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
    var arrayDays = ['Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di'];
    arrayDays.forEach(function(idDay){
        var tdDay = $('<td>',{ scope:'col' });
        var divDay = $('<div>',{ class:'input text' });
        var hiddenDay = $('<input>',{
            name: 'day['+id+']['+idDay+'][id]',
            type: 'hidden'
        });
        var inputDay = $('<input>',{
            id:'day-'+id+'-'+idDay,
            name: 'day['+id+']['+idDay+'][time]',
            type: 'text'
        });
        inputDay.on('input', function() {
            numericer(this);
            updateTotal();
        });
        divDay.append(hiddenDay);
        divDay.append(inputDay);
        tdDay.append(divDay);
        tr.append(tdDay);
    });

    if (id == 0) {
        tr.insertBefore('#total');
    }else{
        tr.insertAfter('#'+(id-1));
    }

    $( ".client" ).change();
}

$('input').on('input', function() {
    numericer(this);
    updateTotal();
});

function numericer(that) {
    var regex = /^([0-9])+([, .])?([0-9]+)?/g;
    var arrayString = $(that).val().match(regex);
    $(that).val(arrayString.join(''));
}
function updateTotal() {
    var arrayDays = ['Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di'];
    var nb = 7;
    arrayDays.forEach(function(idDay){
        var arrayColLu = $('#semainier > tbody > tr > td:nth-child('+nb+')');
        var totalLu = 0;
        for (var i = 0; i < arrayColLu.length-1; i++) {
            var value = $(arrayColLu[i]).children().find('input[type=text]').val();
            if (value == "") {
                value = 0;
            }
            totalLu += parseFloat(value);
        }
        var identifier = '#t'+idDay;
        $(identifier).text(totalLu);
        nb+=1;
    });
}
