<div class="convertisseur">
    <div class="supp left">
        <button id="button-info" type="button" class="btn btn-info" onclick="$( '#table-info' ).toggle();">?</button>
    </div>
    <div id="table-info" style="display:none;" class="panel panel-default col-xs-9 fixe right" onclick="$( '#table-info' ).hide();">
        <div class="panel-body">
            <table class="info">
                <thead>
                    <tr><td>Heure</td><td>Valeur</td></tr>
                </thead>
                <tbody>
                    <tr><td><?= \Cake\Core\Configure::read('profil.1') ?></td><td> Technique ABAP</td></tr>
                    <tr><td><?= \Cake\Core\Configure::read('profil.2') ?></td><td> Technique ABAP expert</td></tr>
                    <tr><td><?= \Cake\Core\Configure::read('profil.3') ?></td><td> Fonctionnel confirmé</td></tr>
                    <tr><td><?= \Cake\Core\Configure::read('profil.4') ?></td><td> Fonctionnel / BI / BC</td></tr>
                    <tr><td><?= \Cake\Core\Configure::read('profil.5') ?></td><td> Expert / CP / Référent</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
