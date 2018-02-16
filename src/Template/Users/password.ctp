<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>

<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Modifier votre Mot de passe');?> <?=  h($user->email); ?></legend>
        <?php
            echo $this->Form->control('mdp',['value' => '']);
            echo $this->Form->control('password2');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
