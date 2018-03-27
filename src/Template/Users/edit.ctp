<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>

<div class="users form large-10 large-10bis medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Modification de : ');?> <span class="text-danger"><?=  h($user->email); ?></span></legend>
        <?php
            echo $this->Form->control('email');
            echo $this->Form->control('prenom');
            echo $this->Form->control('nom');
            // echo $this->Form->control('mdp');
            echo $this->Form->control('actif', ['type' => 'checkbox']);
            echo $this->Form->control('prem_connect', ['type' => 'checkbox', 'class'=>'reset', 'label'=>'Réinitialisation mot de passe']);

            echo $this->Form->control('role', ['type' => 'checkbox', 'label' => ['text'=>'Chef de projet', 'class' => 'text-primary']]);
            echo $this->Form->control('admin', ['type' => 'checkbox', 'label' => ['class' => 'text-danger']]);
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
