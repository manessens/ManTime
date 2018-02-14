<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<!--<li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $user->idu],
                ['confirm' => __('Are you sure you want to delete # {0}?', $user->idu)]
            )
        ?></li> -->
<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Edit User');?> <?=  h($user->email); ?></legend>
        <?php
            echo $this->Form->control('prenom');
            echo $this->Form->control('nom');
            // echo $this->Form->control('email');
            // echo $this->Form->control('mdp');
            echo "<legend> Actif </legend>" ;
            echo $this->Form->checkbox('actif');
            echo "<legend> Première connection </legend>" ;
            echo $this->Form->checkbox('prem_connect');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
