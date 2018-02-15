<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>

<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Edit User');?> <?=  h($user->email); ?></legend>
        <?php
            echo $this->Form->control('prenom');
            echo $this->Form->control('nom');
            // echo $this->Form->control('email');
            // echo $this->Form->control('mdp');
            echo $this->Form->control('actif', ['type' => 'checkbox']);
            echo $this->Form->control('prem_connect', ['type' => 'checkbox']);

            echo $this->Form->control('admin', ['type' => 'checkbox', 'label' => ['class' => 'text-danger']]);
         ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
