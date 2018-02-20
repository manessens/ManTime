<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Profil $profil
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Profil'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="profil form large-9 medium-8 columns content">
    <?= $this->Form->create($profil) ?>
    <fieldset>
        <legend><?= __('Add Profil') ?></legend>
        <?php
            echo $this->Form->control('nom_profil');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
