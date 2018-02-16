$( ".reset" ).on( "click", resetPssw );

function resetPssw(){
    alert( "clicked" );
    BootstrapDialog.show({
        message: "Le mot de passe sera redéfinie par 'Welcome1!'.",
        buttons: [{
            label: 'Button 1',
            title: 'Mouse over Button 1'
        }, {
            label: 'Fermer',
            action: function(dialogItself){
                dialogItself.close();
            }
        }]
    });
}
