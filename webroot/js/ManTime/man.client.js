$(function() {
    init();
    $( "#id_gence" ).change();
});
var xhr;

$( "#id_gence" ).change(function (){
    console.log(this);
    var val = $(this).find('option:selected').val();
    console.log(val);
    $('#linker').attr('data-whatever',val);
});

function init(){
    $('#linkModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var recipient = button.attr('data-whatever')// Extract info from data-* attributes
      var modal = $(this);

      modal.find('.modal-title').text('Chercher le consultant ' + recipient)
      modal.find('.modal-body input').val(recipient)
    });

    $('#ajax').on('submit',function(e){
        e.preventDefault();
        var email = $('#linkModal').find('.modal-body input').val();
        xhr = $.ajax({
            type: "GET",
            url: "/users/getEmployeeFitnet/",
            data: { mail: email },
            beforeSend: function( xhr ) {
                $('#loader').show();
                $('#linkModal').find(".modal-footer button#send").hide();
            }
        }).done(function( data ) {
            if ( !jQuery.isEmptyObject(data) ) {    //success
                $('#id-fit').val(data.employee_id);
                $('#linker').removeClass('btn-primary').addClass('btn-success');
            }else{                                  // fail
                $('#id-fit').val(null);
                $('#linker').removeClass('btn-success').addClass('btn-primary');
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
