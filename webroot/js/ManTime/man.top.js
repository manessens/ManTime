$(function() {
    init();
});

function init(){

    window.onscroll = function(){scrollFunction()};
}

function scrollFunction() {
    if (window.scrollY > 50 && window.scrollY <= (document.documentElement.scrollTop - 150) ) {
        $(".bottom_fix").show();
    } else {
        $(".bottom_fix").hide();
    }
}
