<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>

<div class="col-xs-10 new_content content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Modifier votre Mot de passe');?> <?=  h($user->email); ?></legend>
        <?php
            echo $this->Form->control('mdp',['label' => 'Mot de passe', 'value' => '', 'type' => 'password']);
            echo $this->Form->control('password2', ['label' => 'Confirmation mot de passe', 'type'=>'password']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer'), ['class' => 'btn btn-primary']) ?>
    <?= $this->Form->end() ?>
</div>
