<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>

<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Change password');?> <?=  h($user->email); ?></legend>
        <!-- <?= $this->Form->input('old_password',['type' => 'password' , 'label'=>'Old password'])?>
        <?= $this->Form->input('password1',['type'=>'password' ,'label'=>'Password']) ?>
        <?= $this->Form->input('password2',['type' => 'password' , 'label'=>'Repeat password'])?> -->
        <?php
            echo $this->Form->control('mdp');
            echo $this->Form->input('password2',['type'=>'mdp' ,'label'=>'Repeat password']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
