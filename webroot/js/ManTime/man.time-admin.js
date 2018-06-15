$(function() {
    init();
    $( ".users" ).change();
    alert = false;
    alertVerouillage = false;
    updateTotal();
    first = false;
});
var arrayDays = ['Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di'];
var alertVerouillage;
var first = true;

function init(){
    $('select').each(function(){
        var selected = $( this ).val();
        $(this).children("option [value='"+selected+"']").attr('selected', 'selected');
    })
}


$( "form" ).on('submit',function (e){
    if ($('#validat').prop('checked')) {
        var modal = new ModalWindow({
            Title: "Validation semaine",
            Message: "Vous avez coché la validation pour export, les consultants ne pourront plus modifier leur temps. Êtes-vous sûr de vouloir continuer ?",
            Buttons: [["btn-primary admin", 'Non', 'false'], ["btn-danger admin", 'Oui', 'true']],
            CallBack: function(result, event, formData, ExtraData, rootDiv) {
                if (result === 'false') {
                    alertVerouillage = false;
                }else{
                    $('#validat').prop('checked', false);
                    alertVerouillage = true;
                }
                $( "form" ).submit();
                alertVerouillage = $('#validat').prop('checked');
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
    var tr = $(that).parent().parent();
    var selectClient = $(tr).find('td.cel_client').children();
    $( selectClient ).find('option').each(function() {
        if (val == 0) {
            if ( $( this ).val() == val) {
                $( this ).show();
            }else{
                $( this ).hide();
            }
        }else{
            if ( $.inArray($( this ).val(), optionClients[idu]) != -1 ) {
                $( this ).show();
            }else{
                $( this ).hide();
            }
        }
    });
    if ($( selectClient ).find('option[selected="selected"]').css('display')!='none' && $( selectClient ).find('option[selected="selected"]').length){
        $( selectClient ).val($( selectClient ).find('option[selected="selected"]').val());
    }else{
        if (val == 0 || optionClients[idu] == null) {
            $( selectClient ).val(0);
        }else{
            $( selectClient ).val(optionClients[idu][0]);
        }
    }
    $( selectClient ).change();

    if (first == false) {
        var arrayTr = $('tr[user="'+idu+'"]');
        var arrayId = [];
        var idLine;

        var max = -1;
        var new_val = -1;
        arrayTr.each(function(){
            new_val =  $(this).attr('id');
            if ($.isNumeric(new_val) ) {
                if (new_val > max) {
                    max = new_val;
                }
            }
        });
        idLine = max;
        arrayTr.each(function(){
            arrayId.push(Number($(this).attr('idLine')));
        });
        for (var i = 0; i <= arrayTr.length; i++) {
            if ($.inArray(i, arrayId) == -1) {
                idLine = i;
                break;
            }
        }
        $( tr ).attr('idLine', idLine);
        $( tr ).attr('user', idu);

        $( that ).attr('name', 'users['+idu+']['+idLine+']');
        $( selectClient ).attr('name', 'client['+idu+']['+idLine+']');
        var selectProjet = $(tr).find('td.cel_projet').children();
        $( selectProjet ).attr('name', 'projet['+idu+']['+idLine+']');
        var selectProfil = $(tr).find('td.cel_profil').children();
        $( selectProfil ).attr('name', 'profil['+idu+']['+idLine+']');
        var selectActivit = $(tr).find('td.cel_activit').children();
        $( selectActivit ).attr('name', 'activities['+idu+']['+idLine+']');
        var tdSelectLast = $(tr).find('td.cel_detail');
        var inputDetail = $(tdSelectLast).children('div').children('input');
        $( inputDetail ).attr('name', 'detail['+idu+']['+idLine+']');
        $( inputDetail ).attr('idLine', 'detail-'+idu+'-'+idLine);
        arrayDays.forEach(function(idDay){
            tdSelectLast = $(tdSelectLast).next();
            var inputCurrentText = $(tdSelectLast).children().find('input[type="text"]');
            var inputCurrentHidden = $(tdSelectLast).children().find('input[type="hidden"]');
            $(inputCurrentText).attr('id','day-'+idu+'-'+idLine+'-'+idDay);
            $(inputCurrentText).attr('name','day['+idu+']['+idLine+']['+idDay+'][time]');
            $(inputCurrentHidden).attr('name','day['+idu+']['+idLine+']['+idDay+'][id]');
        });

    }
}


$( ".client" ).change(function(){
    modifyClient(this);
});

function modifyClient (that) {
    var val = $(that).val();
    if (val != 0) {
        var idu = val.split('.')[0];
        var idc = val.split('.')[1];
    }
    var select = $(that).parent().parent().find('td.cel_projet').children();
    $( select ).find('option').each(function() {
        if (val == 0) {
            if ( $( this ).val() == 0) {
                $( this ).show();
            }else{
                $( this ).hide();
            }
        }else {
            if ( $.inArray($( this ).val(), optionProjects[idu+'.'+idc]) != -1 ) {
                $( this ).show();
            }else{
                $( this ).hide();
            }
        }
    });
    if ($( select ).find('option[selected="selected"]').css('display')!='none' && $( select ).find('option[selected="selected"]').length){
        $( select ).val($( select ).find('option[selected="selected"]').val());
    }else{
        if (val == 0) {
            $( select ).val(val);
        }else{
            $( select ).val(optionProjects[idu+'.'+idc][0]);
        }
    }
    $( select ).change();
}

$( ".project" ).change(function(){
    modifyProject(this);
});

function modifyProject(that) {
    var val = $(that).val();
    var idp = val.split('.')[2];
    var select = $( that ).parent().parent().find('td.cel_activit').children();
    $( select ).find('option').each(function() {
        if (val == 0) {
            if ( $( this ).val() == 0) {
                $( this ).show();
            }else{
                $( this ).hide();
            }
        }else {
            if ( $.inArray($( this ).val(), optionActivits[idp]) != -1 ) {
                $( this ).show();
            }else{
                $( this ).hide();
            }
        }
    });
    if ($( select ).find('option[selected="selected"]').css('display')!='none' && $( select ).find('option[selected="selected"]').length){
        $( select ).val($( select ).find('option[selected="selected"]').val());
    }else{
        if (val == 0) {
            $( select ).val(val);
        }else{
            $( select ).val(optionActivits[idp][0]);
        }
    }
    var select2 = $( that ).parent().parent().find('td.cel_profil').children();
    $( select2 ).find('option').each(function() {
        if (val == 0) {
            if ( $( this ).val() == 0) {
                $( this ).show();
            }else{
                $( this ).hide();
            }
        }else {
            if ( $.inArray($( this ).val(), optionProfils[idp]) != -1 ) {
                $( this ).show();
            }else{
                $( this ).hide();
            }
        }
    });
    if ($( select2 ).find('option[selected="selected"]').css('display')!='none' && $( select2 ).find('option[selected="selected"]').length){
        $( select2 ).val($( select2 ).find('option[selected="selected"]').val());
    }else{
        if (val == 0) {
            $( select2 ).val(val);
        }else{
            $( select2 ).val(optionProfils[idp][0]);
        }
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
    var id = 0;
    idUser = optionUsers[0];
    var tr = $('<tr>', {
        idLine: id,
        user: idUser
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
        class:'users',
        name:'users['+idUser+']['+id+']'
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
        name:'client['+idUser+']['+id+']'
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
        name:'projet['+idUser+']['+id+']'
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
        name:'profil['+idUser+']['+id+']'
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
        name:'activities['+idUser+']['+id+']'
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
    //Detail
    var tdDetail = $('<td>',{
        class:'cel_detail',
        scope:'col'
    });
    var divDetail = $('<div>',{
        class:'input text',
    });
    var inputDetail = $('<input>',{
        type:'text',
        name:'detail['+idUser+']['+id+']',
        id: 'detail-'+idUser+'-'+id
    });
    divDetail.append(inputDetail);
    tdDetail.append(divDetail);
    tr.append(tdDetail);
    // Days
    arrayDays.forEach(function(idDay){
        var tdDay = $('<td>',{ scope:'col' });
        var divDay = $('<div>',{ class:'input text' });
        var hiddenDay = $('<input>',{
            name: 'day['+idUser+']['+id+']['+idDay+'][id]',
            type: 'hidden'
        });
        var inputDay = $('<input>',{
            id:'day-'+idUser+'-'+id+'-'+idDay,
            name: 'day['+idUser+']['+id+']['+idDay+'][time]',
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

    tr.insertBefore('#total');

    selectUser.change();
}

$('.numericer').on('input', function() {
    numericer(this);
    updateTotal();
});

function numericer(that) {
    var regex = /^([0-9])+([.])?([0-9]+)?/g;
    var arrayString = $(that).val().match(regex);
    $(that).val(arrayString.join(''));
}
function updateTotal() {
    var nb = 8;
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
        $(identifier).text(Math.round(totalLu * 100)/100);
        nb+=1;
    });
}
