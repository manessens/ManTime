<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>

<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Add User') ?></legend>
        <?php
            echo $this->Form->control('prenom');
            echo $this->Form->control('nom');
            echo $this->Form->control('email');
            echo $this->Form->control('mdp');
            echo $this->Form->control('actif');
            echo $this->Form->control('prem_connect');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
