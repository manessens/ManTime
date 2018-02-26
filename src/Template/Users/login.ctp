
<div class="users index large-10 medium-8 columns content">
    <legend>Login</legend>
    <?= $this->Form->create() ?>
    <?= $this->Form->control('email') ?>
    <?= $this->Form->control('mdp', ['type'=>'password', 'label'=>'Mot de passe']) ?>
    <?= $this->Form->button('Connexion', ['class' => 'btn btn-primary']) ?>
    <?= $this->Form->end() ?>
</div>
