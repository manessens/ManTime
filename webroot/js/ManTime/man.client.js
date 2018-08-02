$(function() {
    init();
    $( "#id_agence" ).change();
});
var xhr;

$( "#id_agence" ).change(function (){
    var val = $(this).find('option:selected').val();
    $('#linker').attr('data-whatever',val);
});

function init(){
    $('#linkModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var recipient = button.attr('data-whatever')// Extract info from data-* attributes
      var modal = $(this);

      modal.find('.modal-title').text('Chercher le consultant ' + recipient)
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
function updateSelect(data){
    $('#liste_fitnet option').removeAttr('readonly');
    console.log(data);
}

function eraseSelect(){
    $('#liste_fitnet option').remove();
    $('#liste_fitnet option').attr('readonly','readonly');
    console.log("erased");
}
