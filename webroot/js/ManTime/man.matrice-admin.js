$(function() {
    init();
});

function init(){
    $('.input.number').each(function(){
        var input = $( this ).children('input').prop('disabled', false);
    })
}
