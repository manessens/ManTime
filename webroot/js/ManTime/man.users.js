$(function() {
    init();
});
var xhr;
// **INITIALISATION **
function initResetPswd(){
    $( ".reset" ).click(function (){
        if ($(this).is(':checked')) {
            $('#myModal').modal('show');
        };
    });
}

function initResetFitnet(){
    $( "#resetter" ).click(resetFitnet);
}

function initEmailModal(){
    $('#email').on('change',function(e){
        var input = $('#email')
        $('#linker').attr('data-whatever',input.val());
    })
}

function initSelectRole(){
    $('select[name="role"] option').addClass('text-default');
    $('select[name="role"] option[value="20"]').removeClass('text-default').addClass('text-primary');
    $('select[name="role"] option[value="50"]').removeClass('text-default').addClass('text-danger');
    $('select[name="role"]').on('change',function(){
        var select = $(this);
        switch (select.val()) {
            case 50:
                select.removeClass()
                select.addClass('text-danger')
                break;
            case 20:
                select.removeClass()
                select.addClass('text-primary')
                break;
            default:
                select.removeClass()
        }
    })
}

function init(){
    initResetPswd();
    initResetFitnet();
    initEmailModal();
    initSelectRole();

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
                resetFitnet();
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

// **FUNCTION**
function resetFitnet(){
    $('#id-fit').val(null);
    $('#linker').removeClass('btn-success').addClass('btn-primary');
}
