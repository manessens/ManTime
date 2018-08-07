$(function() {
    init();
});

function initDatePicker(){
    $( ".datepicker" ).each(function() {
        if ($( this ).attr('value').length > 10) {
            $( this ).attr('value', moment($( this ).attr('value'), "DD/MM/YYYY hh:mm").format("YYYY-MM-DD"));
        }
        $( this ).attr('type', 'date');
    });
}

function init(){
    initDatePicker();
    initMultiple();

    $('#linkModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var recipient = button.attr('data-whatever')// Extract info from data-* attributes
      var modal = $(this);

      modal.find('.modal-title').text('Obtenir la liste fitnet des projets')
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
