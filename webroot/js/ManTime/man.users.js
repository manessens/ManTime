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

        $('#loader').show();
        $('#linkModal').find(".modal-footer button#send").hide();

        xhr = $.ajax({
            type: "GET",
            url: "/users/getEmployeeFitnet/",
            data: { mail: email },
        }).done(function( data ) {
            if (data.lenght > 0 ) {
                $('#linker').class('btn btn-success');
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
