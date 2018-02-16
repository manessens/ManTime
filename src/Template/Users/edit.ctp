<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>

<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Modification de : ');?> <span class="text-danger"><?=  h($user->email); ?></span></legend>
        <?php
            echo $this->Form->control('prenom');
            echo $this->Form->control('nom');
            // echo $this->Form->control('email');
            // echo $this->Form->control('mdp');
            echo $this->Form->control('actif', ['type' => 'checkbox']);
            echo $this->Form->control('prem_connect', ['type' => 'checkbox', 'class'=>'reset']);

            echo $this->Form->control('admin', ['type' => 'checkbox', 'label' => ['class' => 'text-danger']]);
         ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>


<?php echo $this->Html->script('ManTime/man.users.js'); ?>
