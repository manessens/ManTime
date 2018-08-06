$(function() {
    init();
});

function init(){

    window.onscroll = function(){scrollFunction()};
}

function scrollFunction() {
    console.log(window.scrollY);
    if (window.scrollY > 20 ) {
        $(".bottom_fix").show();
    } else {
        $(".bottom_fix").hide();
    }
}
