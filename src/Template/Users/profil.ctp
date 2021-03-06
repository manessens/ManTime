<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>

<div class="col-xs-10 new_content content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend>
            <?= __('Profil de : ');?> <span class="text-danger"><?=  h($user->fullname); ?> </span>
            <?= $this->element('link2fitnet', ['idf'=>$user->id_fit]) ?>
        </legend>
        <table class="vertical-table">
            <tr>
                <th scope="row"><?= __('Email') ?></th>
                <td><?= h($user->email) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Prenom') ?></th>
                <td><?= h($user->prenom) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Nom') ?></th>
                <td><?= h($user->nom) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Origine') ?></th>
                <td><?= h($user->origine->nom_origine) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Rôle') ?></th>
                <td>
                    <?= $this->element('roleselect', ['role'=>$this->Number->format($user->role)]) ?>
                </td>
            </tr>
        </table>
        <?php
            if ($user->prem_connect) {
                echo "<div class='header'>Le mot de passe à été réinitialiser, veuillez vous reconnecter pour choisir un nouveau mot de passe.</div>";
            }else{
                echo $this->Form->control('prem_connect', ['type' => 'checkbox', 'class'=>'reset', 'label'=>'Réinitialisation mot de passe']);
            }
         ?>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer'), ['class' => 'btn btn-warning right']) ?>
    <?= $this->Form->end() ?>
</div>


<?php echo $this->Html->script('ManTime/man.users.js'); ?>
<div class="modal" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Réinitialiser le mot de passe</h3>
            </div>
            <div class="modal-body">
                Le mot de passe sera définie à "Welcome1!".
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
