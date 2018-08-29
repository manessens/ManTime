$(function() {
    xhr = null;
    init();
    if (/chrom(e|ium)/.test(navigator.userAgent.toLowerCase())) {
        initWeeker();
    }
});
var xhr;

// **INITIALISATION**

function initWeeker(){
    $('#select-week').show();
    $('#select-select-weekandyear').hide();
    $('#select-week').on('change', function(){
        var weeker = $(this).val();
        var annee = weeker.substring(0, 4);
        var week = weeker.substring(6, 8);
        $('#select-select-weekandyear').find('input#week').val(week);
        $('#select-select-weekandyear').find('input#year').val(annee);
    });
}

function init(){

    $('.btn-loader').on('click',function(e){
        var that = this;
        var idu = $(that).attr('data-idu');
        var target = 'loader-'+idu;
        var activ = $(that).attr('data-activ');
        xhr = $.ajax({
            type: "POST",
            url: "/Utils/set"+activ+"User/",
            data: { user: idu },
            beforeSend: function( xhr ) {
                $('#'+target).show();
                $(that).hide();
            }
        }).done(function( data ) {
            if ( !jQuery.isEmptyObject(data) ) {    //success
                // updateSelect(data);
            }else{                                  // fail
                // eraseSelect();
            }
        }).always(function(){
            $('#'+target).hide();
            $(that).show();
        });
    });

}

// **FUNCION**
