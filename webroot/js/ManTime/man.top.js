$(function() {
    init();
});

function init(){

    window.onscroll = function() {scrollFunction()};
}

function scrollFunction() {
    if (window.scrollY > 20 ) {
        $("#bottom_fix").style.display = "block";
    } else {
        $("#bottom_fix").style.display = "none";
    }
}
