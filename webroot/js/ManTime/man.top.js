$(function() {
    init();
});

function init(){

    window.onscroll = function(){scrollFunction()};
}

function scrollFunction() {
    if (window.scrollY > 200 && window.scrollY <= (document.documentElement.scrollTopMax - 150) ) {
        $(".bottom_fix").show();
    } else {
        $(".bottom_fix").hide();
    }
}
