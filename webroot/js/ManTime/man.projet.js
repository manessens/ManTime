$(function() {
    init();
    $('#liste_fitnet').select2();
});
var xhr;

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
            if ( !jQuery.isEmptyObject(data) ) {    //success
                updateSelect(data['select']);
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
    $('#date-debut').removeAttr('readonly');
    $('#date-fin').removeAttr('readonly');
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
