<<<<<<< HEAD
<?php $this->request->session->delete('Flash'); ?>
=======

>>>>>>> parent of 50c12a2... test flas
<div class="users index large-9 medium-8 columns content">
    <legend>Login</legend>
    <?= $this->Form->create() ?>
    <?= $this->Form->control('email') ?>
    <?= $this->Form->control('mdp', ['type'=>'password', 'label'=>'Mot de passe']) ?>
    <?= $this->Form->button('Connexion') ?>
    <?= $this->Form->end() ?>
</div>
