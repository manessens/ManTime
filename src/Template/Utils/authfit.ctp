<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>

<div class="col-xs-12 new_content content">
    <legend>
        <?= $this->Html->link(__('<'), ['action' => 'index'], ['class' => 'btn btn-info']) ?><?= __('Utilitaire - ') ?><span class="text-danger"><?= __('Authorisation Fitnet') ?></span>
    </legend>
    <div class="col-xs-10">
        <?= $this->Form->create($form) ?>
        <fieldset>
            <?php
                echo $this->Form->control('login',['label' => 'Login Fitnet']);
                echo $this->Form->control('password', ['label' => 'Password Fitnet', 'type'=>'password']);
            ?>
            <?= $this->Form->button(__('Autoriser pour 1h'), ['class' => 'btn btn-primary']) ?>
        </fieldset>
    </div>
</div>

<?php
    echo $this->Html->css('ManTime/man.loader.css');
    echo $this->Html->script('ManTime/man.utils.js');
?>
