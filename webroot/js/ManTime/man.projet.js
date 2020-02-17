$(function() {
    init();
    $('#liste_fitnet').select2();
    initSelectEdit();
    // initChangeFact();
});
var xhr;

var extandData;

function initDatePicker(){
    $( ".datepicker" ).each(function() {
        if ($( this ).attr('value').length > 10) {
            $( this ).attr('value', moment($( this ).attr('value'), "DD/MM/YYYY hh:mm").format("YYYY-MM-DD"));
        }
        $( this ).attr('type', 'date');
    });
}

function initcChangeClient(){
    $( "#id_client" ).on('change', function (e){
        var val = $(this).find('option:selected').val();
        $('#linker').attr('data-whatever',val);
    }).change();
}

function initIgnorFitnet(){
    $( "#ignore-fit" ).on('change', function (e){
        if($(this).is(':checked')){
            $(this).attr('data-value', $('#id-fit').val());
            $("#content-fitnet-link").hide();
            resetFitnet();
        }else{
            $('#id-fit').val($(this).attr('data-value'));
            initSelectEdit()
            $("#content-fitnet-link").show();
        }
    });
}

function initResetSelect(){
    $('#resetter').on('click',function(e){
        $("#liste_fitnet").val(null).trigger("change");
    });
}

function initSelectEdit(){
    if ($('#id-fit').val() != null) {
        var value = $('#linker').attr('data-whatever');
        $('#linkModal').find('.modal-body select option[value='+value+']').prop('selected', true);
        $('#ajax').submit();
    }
}

function initChangeSelect2(){
    $("#liste_fitnet").on("change", function(e) {
        var val = $("#liste_fitnet").find(':selected').val();
        $('#id-fit').val(val);
        if (val != null ) {
            $('#linker').removeClass('btn-primary').addClass('btn-success');
            $('#date-debut').attr('readonly','readonly');
            $('#date-fin').attr('readonly','readonly');
            $('#nom-projet').attr('readonly','readonly');

            $('#date-debut').val(moment(extandData[val].beginDate, "DD/MM/YYYY hh:mm").format("YYYY-MM-DD"));
            $('#date-fin').val(moment(extandData[val].endDate, "DD/MM/YYYY hh:mm").format("YYYY-MM-DD"));
            $('#nom-projet').val(extandData[val].title);
        }else{
            resetFitnet();
        }
    });
}

// function initChangeFact() {
//     $('select[name="idf"]').on('change',function(e){
//         if ( "2" == nfArray[$(this).val()][0] ) {
//             $('#linker').attr('disabled', 'disabled');
//             $('#resetter').attr('disabled', 'disabled');
//             $('#liste_fitnet').attr('disabled', 'disabled');
//             $('#resetter').click();
//             $('#id-fit').val(nfArray[$(this).val()][1]);
//         }else{
//             $('#linker').removeAttr('disabled');
//             $('#resetter').removeAttr('disabled');
//             $('#liste_fitnet').removeAttr('disabled');
//             $('#resetter').click();
//         }
//     });
//     if ($('#id-fit').val() == "" || !isFacturable() ) {
//         $('select[name="idf"]').change();
//     }
// }

function resetFitnet(){
    $('#date-debut').removeAttr('readonly');
    $('#date-fin').removeAttr('readonly');
    $('#linker').removeClass('btn-success').addClass('btn-primary');
}

function init(){
    initDatePicker();
    initMultiple();
    initcChangeClient();
    initChangeSelect2();
    initResetSelect();
    initIgnorFitnet();

    $('#linker').on('click',function(e){
        e.preventDefault();
        var id_client = $('#linker').attr('data-whatever');;
        xhr = $.ajax({
            type: "GET",
            url: "/projet/getProjectVsa/",
            data: { client: id_client },
            beforeSend: function( xhr ) {
                $('#loader').show();
            }
        }).done(function( data ) {
            if ( !jQuery.isEmptyObject(data['select']) ) {    //success
                if (data['select']=='error')
                    updateSelect(data);
                }
            }else{                                  // fail
                eraseSelect();
            }
        }).always(function(){
            $('#loader').hide();
        });
    });

    $('#resetter').on('click', function (e) {
        if (xhr != undefined) {
            xhr.abort();
        }
    });
}

function updateSelect(data){
    eraseSelect();
    $('#liste_fitnet').select2({
        data: data['select']
    });
    extandData = data['projects'];
    var id_fit = $('#id-fit').val();
    if ( id_fit != null ) {
        $('#liste_fitnet').val(id_fit);
        $('#liste_fitnet').trigger('change'); // Notify any JS components that the value changed
    }else{
        $("#liste_fitnet").val(null).trigger("change");
    }
}

function eraseSelect(){
    $('#liste_fitnet option').remove();
    extandData = [];
}

function initMultiple(){
    $('.multiple option').mousedown(function(e) {
        e.preventDefault();
        $(this).prop('selected', !$(this).prop('selected'));
        return false;
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
}
