$(function() {
    init();
    $( ".client" ).change();
    alert = false;
    alertVerouillage = false;
    updateTotal();
});
var arrayDays = ['Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di'];
var alert;
var alertVerouillage;

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
            Message: "Vous avez coché la validation, vous ne pourrez plus faire de Modification par la suite, êtes vous sûr de vouloir continuer ?",
            Buttons: [["btn-primary admin", 'Non', 'false'], ["btn-danger admin", 'Oui', 'true']],
            CallBack: function(result, event, formData, ExtraData, rootDiv) {
                if (result === 'false') {
                    $('#validat').prop('checked', false);
                    alertVerouillage = true;
                }else{
                    alertVerouillage = false;
                }
                $( "form" ).submit();
                alertVerouillage = $('#validat').prop('checked');
            },
            Center: true,
            AllowClickAway: false
        });
        modal.Show();
    };
    if (alert) {
        var modal = new ModalWindow({
            Title: "Attention saisie journée",
            Message: "Attention, vous avez saisie un total qui dépasse une journée pleine, êtes vous sûr de vouloir continuer ?",
            Buttons: [["btn-primary admin", 'Non', 'false'], ["btn-danger admin", 'Oui', 'true']],
            CallBack: function(result, event, formData, ExtraData, rootDiv) {
                if (result === 'true') {
                    alert = false;
                    $( "form" ).submit();
                }
            },
            Center: true,
            AllowClickAway: false
        });
        modal.Show();
    };
    if (alert || alertVerouillage) {
        e.preventDefault();
    }
});

$( "#validat" ).click(function(){
    alertVerouillage = $('#validat').prop('checked');
});

$( ".client" ).change(function(){
    modifyClient(this);
});

function modifyClient (that) {
    var val = $(that).val();
    var idc = val.split('.')[1];
    var select = $(that).parent().parent().find('td.cel_projet').children();
    $( select ).find('option').each(function() {
        if (val == 0) {
            if ( $( this ).val() == 0) {
                $( this ).show();
            }else{
                $( this ).hide();
            }
        }else {
            if ( $.inArray($( this ).val(), optionProjects[idc]) != -1 ) {
                $( this ).show();
            }else{
                $( this ).hide();
            }
        }
    });
    if ($( select ).find('option[selected="selected"]').css('display')!='none' && $( select ).find('option[selected="selected"]').length ){
        $( select ).val($( select ).find('option[selected="selected"]').val());
    }else{
        if (val == 0) {
            $( select ).val(val);
        }else{
            $( select ).val(optionProjects[idc][0]);
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
    if ($( select ).find('option[selected="selected"]').css('display')!='none' && $( select ).find('option[selected="selected"]').length ){
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
    if ($( select2 ).find('option[selected="selected"]').css('display')!='none' && $( select2 ).find('option[selected="selected"]').length ){
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
    updateTotal();
}

$( "#add" ).click(function(){
    addLine(this);
});

function findLastId(){
    var max = -1;
    var new_val = -1;
    $('#semainier > tbody > tr ').each(function(){
        new_val =  $(this).attr('id');
        if ($.isNumeric(new_val) ) {
        console.log(new_val);
            if (new_val > max) {
                max = new_val;
            }
        }
    });
    return max;
}

function addLine(that) {
    var id = findLastId();
    id += 1;
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
        name:'detail['+id+']',
        id: 'detail-'+id
    });
    divDetail.append(inputDetail);
    tdDetail.append(divDetail);
    tr.append(tdDetail);
    // Days
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

    selectClient.change();
}

$('.numericer').on('input', function() {
    numericer(this);
    updateTotal();
});

function numericer(that) {
    var regex = /^([0-9])+([.])?([0-9]+)?/g;
    var arrayString = $(that).val().match(regex);
    if (arrayString == null) {
        $(that).val('');
    }else{
        $(that).val(arrayString.join(''));
    }
}
function updateTotal() {
    var nb = 7;
    var noalert = true;
    arrayDays.forEach(function(idDay){
        var arrayColLu = $('#semainier > tbody > tr > td:nth-child('+nb+')');
        var totalLu = 0;
        for (var i = 0; i < arrayColLu.length-1; i++) {
            var value = $(arrayColLu[i]).children().find('input[type=text]').val();
            if ($.isNumeric(value) == false) {
                value = 0;
            }
            totalLu += parseFloat(value);
        }
        var identifier = '#t'+idDay;
        $(identifier).text(Math.round(totalLu * 100)/100);
        if (identifier > 1) {
            $(identifier).css({"color": "red"});
            noalert = false;
        }else{
            $(identifier).css({"color": "black"});
            noalert = noalert && true;
        }
        nb+=1;
    });
    if (!noalert) {
        alert = true;
    }else{
        alert = false;
    }
}
