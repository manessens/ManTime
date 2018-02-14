
<div class="users index large-9 medium-8 columns content">
    <h1>Login</h1>
    <?= $this->Form->create() ?>
    <?= $this->Form->control('email') ?>
    <?= $this->Form->control('mdp') ?>
    <?= $this->Form->button('Connexion') ?>
    <?= $this->Form->end() ?>
</div>
