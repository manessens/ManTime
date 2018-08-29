$(function() {
    xhr = null;
    init();
    if (/chrom(e|ium)/.test(navigator.userAgent.toLowerCase())) {
        initWeeker();
    }
});

// **INITIALISATION**

function initWeeker(){
    $('#select-week').show();
    $('#select-weekandyear').hide();
    $('#select-week').on('change', function(){
        var weeker = $(this).val();
        var annee = weeker.substring(0, 4);
        var week = weeker.substring(6, 8);
        $('#select-weekandyear').find('input#week').val(week);
        $('#select-weekandyear').find('input#year').val(annee);
    });
}

function init(){

    $('.btn-loader').on('click',function(e){
        var that = this;
        var idu = $(that).attr('data-idu');
        var target = 'loader-'+idu;
        var activ = $(that).attr('data-activ');
        var nsemaine = $('#nsemaine').text();
        var nannee = $('#nannee').text();
        $.ajax({
            type: "POST",
            url: "/Utils/set"+activ+"User/",
            data: { user: idu, semaine: nsemaine, annee: nannee },
            beforeSend: function( xhr ) {
                $('#'+target).show();
                $(that).hide();
            }
        }).done(function( data ) {
            if ( data ) {    //success
                $('form').submit();
            }else{          // fail
                addError(that);
            }
        }).always(function(){
            $('#'+target).hide();
            $(that).show();
        });
    });

}

// **FUNCION**
function addError(button) {

    var div = $('<div>', {
        class: 'error_ajax',
        text: 'Une erreur est survenue, retenter ultérieurement.'
    });
    console.log($(button).parent());
    $(button).parent().append(div);

}
