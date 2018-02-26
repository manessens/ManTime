<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>

<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Profil de : ');?> <span class="text-danger"><?=  h($user->email); ?></span></legend><table class="vertical-table">
            <tr>
                <th scope="row"><?= __('Prenom') ?></th>
                <td><?= h($user->prenom) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Nom') ?></th>
                <td><?= h($user->nom) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Actif') ?></th>
                <td>
                    <?php $this->set('test', $this->Number->format($user->admin) ) ?>
                    <?= $this->element('tagYN') ?>
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
    <?= $this->Form->button(__('Enregistrer'), ['class' => 'btn btn-warning']) ?>
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