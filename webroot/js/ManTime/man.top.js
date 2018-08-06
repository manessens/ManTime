$(function() {
    init();
});

function init(){

    window.onscroll = function() {scrollFunction()};
}

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        $("#bottom-fix").style.display = "block";
    } else {
        $("#bottom-fix").style.display = "none";
    }
}
