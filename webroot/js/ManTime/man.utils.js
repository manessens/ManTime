$(function() {
    xhr = null;
    init();
});
var xhr;

// **INITIALISATION**
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
