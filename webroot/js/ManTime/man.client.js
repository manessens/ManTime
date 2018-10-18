$(function() {
    init();
    $('#liste_fitnet').select2();
    initResetSelect();
    initSelectEdit();
});
var xhr;

// **INITIALISATION**
function initcChangeAgence(){
    $( "#id_agence" ).on('change', function (e){
        var val = $(this).find('option:selected').val();
        $('#linker').attr('data-whatever',val);
    }).change();
}

function initChangeSelect2(){
    $("#liste_fitnet").on("change", function(e) {
        var val = $("#liste_fitnet").find(':selected').val();
        $('#id-fit').val(val);
        if (val != null) {
            $('#linker').removeClass('btn-primary').addClass('btn-success');
        }
    });
}

function initResetSelect(){
    $('#resetter').on('click',function(e){
        $("#liste_fitnet").val(null).trigger("change");
        $('#linker').removeClass('btn-success').addClass('btn-primary');
    });
}

function initSelectEdit(){
    if ($('#id-fit').val() != null) {
        var value = $('#linker').attr('data-whatever');
        $('#linkModal').find('.modal-body select option[value='+value+']').prop('selected', true);
        $('#ajax').submit();
    }
}

function init(){
    initChangeSelect2();
    initcChangeAgence();

    $('#linkModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var recipient = button.attr('data-whatever')// Extract info from data-* attributes
      var modal = $(this);

      modal.find('.modal-title').text('Obtenir la liste fitnet des clients')
      modal.find('.modal-body select option[value='+recipient+']').prop('selected', true);
    });

    $('#ajax').on('submit',function(e){
        e.preventDefault();
        var id_agence = $('#linkModal').find('.modal-body select option:selected').val();
        xhr = $.ajax({
            type: "GET",
            url: "/client/getCustomerFitnet/",
            data: { agence: id_agence },
            beforeSend: function( xhr ) {
                $('#loader').show();
                $('#linkModal').find(".modal-footer button#send").hide();
            }
        }).done(function( data ) {
            if ( !jQuery.isEmptyObject(data) ) {    //success
                updateSelect(data);
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

// **FUNCION**
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
}
