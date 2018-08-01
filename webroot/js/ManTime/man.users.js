$(function() {
    init();
});
var xhr;

$( ".reset" ).click(function (){
    if ($(this).is(':checked')) {
        $('#myModal').modal('show');
    };
});

function init(){
    $('#linkModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var recipient = button.attr('data-whatever')// Extract info from data-* attributes
      var modal = $(this);

      modal.find('.modal-title').text('Chercher le consultant ' + recipient)
      modal.find('.modal-body input').val(recipient)
    });

    $('#linkModal').find(".modal-footer button#send").on('click',function(e){
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
            if ( !jQuery.isEmptyObject(data) ) {
                $('#id-fit').val(data.employee_id);
                $('#linker').removeClass('btn-primary');
                $('#linker').addClass('btn-success');
            }else{
                $('#id-fité').val("");
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
