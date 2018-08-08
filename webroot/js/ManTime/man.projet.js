$(function() {
    init();
    $('#liste_fitnet').select2();
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

function initResetSelect(){
    $('#date-debut').removeAttr('readonly');
    $('#date-fin').removeAttr('readonly');
    $('#resetter').on('click',function(e){
        $("#liste_fitnet").val(null).trigger("change");
        $('#linker').removeClass('btn-success').addClass('btn-primary');
    });
}

function initChangeSelect2(){
    $("#liste_fitnet").on("change", function(e) {
        var val = $("#liste_fitnet").find(':selected').val();
        $('#id-fit').val(val);
        if (val != null) {
            $('#linker').removeClass('btn-primary').addClass('btn-success');
            $('#date-debut').attr('readonly','readonly');
            $('#date-fin').attr('readonly','readonly');

            $('#date-debut').val(moment(extandData[val].beginDate, "DD/MM/YYYY hh:mm").format("YYYY-MM-DD"));
            $('#date-fin').val(moment(extandData[val].endDate, "DD/MM/YYYY hh:mm").format("YYYY-MM-DD"));
            $('#nom-projet').val(extandData[val].title);
        }
    });
}

function init(){
    initDatePicker();
    initMultiple();
    initcChangeClient();
    initChangeSelect2();
    initResetSelect();

    $('#linkModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var recipient = button.attr('data-whatever')// Extract info from data-* attributes
        var modal = $(this);

        modal.find('.modal-title').text('Obtenir la liste fitnet des projets')
        modal.find('.modal-body select option[value='+recipient+']').prop('selected', true);
    });

    $('#ajax').on('submit',function(e){
        e.preventDefault();
        var id_client = $('#linkModal').find('.modal-body select option:selected').val();
        xhr = $.ajax({
            type: "GET",
            url: "/projet/getProjectFitnet/",
            data: { client: id_client },
            beforeSend: function( xhr ) {
                $('#loader').show();
                $('#linkModal').find(".modal-footer button#send").hide();
            }
        }).done(function( data ) {
            if ( !jQuery.isEmptyObject(data['select']) ) {    //success
                updateSelect(data['select']);
                extandData = data['projects'];
            }else{                                  // fail
                eraseSelect();
            }
        }).always(function(){
            $('#linkModal').modal('hide');
            $('#loader').hide();
            $('#linkModal').find(".modal-footer button#send").show();
        });
    });

    $('#linkModal').on('hide.bs.modal', function (e) {
        if (xhr != undefined) {
            xhr.abort();
        }
        $('#loader').hide();
        $('#linkModal').find(".modal-footer button#send").show();
    });
}

function updateSelect(data){
    eraseSelect();
    $('#liste_fitnet').select2({
        data: data
    });
    var id_fit = $('#id-fit').val();
    if (id_fit != null) {
        $('#liste_fitnet').val(id_fit);
        $('#liste_fitnet').trigger('change'); // Notify any JS components that the value changed
    }else{
        $("#liste_fitnet").change();
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
