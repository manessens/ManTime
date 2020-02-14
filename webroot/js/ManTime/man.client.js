$(function() {
    init();
    $('#liste_vsa').select2();
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
    $("#liste_vsa").on("change", function(e) {
        var val = $("#liste_vsa").find(':selected').val();
        $('#id-fit').val(val);
        if (val != null) {
            $('#linker').removeClass('btn-primary').addClass('btn-success');
        }
    });
}

function initResetSelect(){
    $('#resetter').on('click',function(e){
        $("#liste_vsa").val(null).trigger("change");
        $('#linker').removeClass('btn-success').addClass('btn-primary');
    });
}

function initSelectEdit(){
    if ($('#id-fit').val() != null) {
        var value = $('#linker').attr('data-whatever');
        $('#linker').click();
    }
}

function init(){
    initChangeSelect2();
    initcChangeAgence();

    $('#linker').on('click',function(e){
        e.preventDefault();
        xhr = $.ajax({
            type: "GET",
            url: "/client/getCustomerVsa/",
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
            $('#loader').hide();
        });
    });

    $('#resetter').on('click', function (e) {
        if (xhr != undefined) {
            xhr.abort();
        }
    });
}

// **FUNCION**
function updateSelect(data){
    eraseSelect();
    $('#liste_vsa').select2({
        data: data
    });
    var id_fit = $('#id-fit').val();
    if (id_fit != null) {
        $('#liste_vsa').val(id_fit);
        $('#liste_vsa').trigger('change'); // Notify any JS components that the value changed
    }else{
        $("#liste_vsa").change();
    }
}

function eraseSelect(){
    $('#liste_vsa option').remove();
}
