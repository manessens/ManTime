$( ".reset" ).click(function (){
    if ($(this).is(':checked')) {
        $('#myModal').modal('show');
    };
});

$('#linkModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.attr('data-whatever')// Extract info from data-* attributes

  var modal = $(this);

  modal.find('.modal-title').text('Chercher le consultant ' + recipient)
  modal.find('.modal-body input').val(recipient)
})


$('#linkModal').on('click',function(e){
    console.log(modal.find('.modal-body input').val());

    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
})
