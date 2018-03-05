<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>

<div class="users form large-10 large-10bis medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __("Création d'un Consultant") ?></legend>
        <?php
            echo $this->Form->control('prenom');
            echo $this->Form->control('nom');
            echo $this->Form->control('email');
            echo $this->Form->control('mdp', ['value' => 'Welcome1!']);
            echo $this->Form->control('actif', ['type' => 'checkbox']);
            echo $this->Form->control('admin', ['type' => 'checkbox', 'label' => ['class' => 'text-danger']]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer'), ['class' => 'btn btn-warning']) ?>
    <?= $this->Form->end() ?>
</div>
