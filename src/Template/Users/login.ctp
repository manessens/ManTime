
<div class="col-xs-12 new_content content">
    <div class='col-xs-4'>
        <legend>Login</legend>
        <?= $this->Form->create() ?>
        <?= $this->Form->control('email') ?>
        <?= $this->Form->control('mdp', ['type'=>'password', 'label'=>'Mot de passe']) ?>
        <?= $this->Form->button('Connexion', ['class' => 'btn btn-primary']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
