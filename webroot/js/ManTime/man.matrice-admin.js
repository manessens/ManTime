$(function() {
    init();
});
var modify;

function init(){
    modify = false;
    $('.input.number').each(function(){
        var input = $( this ).children('input').prop('disabled', false);
    })
}

$('.input.number').on('input', function() {
    modify = true
});

$( "form" ).on('submit',function (e){
    if (modify) {
        var modal = new ModalWindow({
            Title: "Matrice modifiée",
            Message: "Vous avez modifié les valeurs d'une matrice existente, les temps déjà liés à cette matrice seront impactés, êtes-vous sûr de vouloir continuer ?",
            Buttons: [["btn-primary admin", 'Non', 'false'], ["btn-danger admin", 'Oui', 'true']],
            CallBack: function(result, event, formData, ExtraData, rootDiv) {
                if (result === 'false') {
                    modify = true;
                }else{
                    modify = false;
                }
                $( "form" ).submit();
            },
            Center: true,
            AllowClickAway: false
        });
        modal.Show();
    };
    if (modify) {
        e.preventDefault();
    }
});
