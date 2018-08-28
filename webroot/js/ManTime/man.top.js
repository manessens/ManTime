$(function() {
    init();
    if (/chrom(e|ium)/.test(navigator.userAgent.toLowerCase())) {
        initWeeker();
    }
});

function init(){

    window.onscroll = function(){scrollFunction()};
}

function scrollFunction() {
    if (window.scrollY > 200 && window.scrollY <= ((document.documentElement.scrollHeight - window.innerHeight) - 150) ) {
        $(".bottom_fix").show();
    } else {
        $(".bottom_fix").hide();
    }
}

function initWeeker(){
    $('#select-week').show();
    $('#select-week').on('change', function(e){
        
    })
}
