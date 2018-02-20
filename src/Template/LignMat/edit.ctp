<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LignMat $lignMat
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $lignMat->id_ligne],
                ['confirm' => __('Are you sure you want to delete # {0}?', $lignMat->id_ligne)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Lign Mat'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="lignMat form large-9 medium-8 columns content">
    <?= $this->Form->create($lignMat) ?>
    <fieldset>
        <legend><?= __('Edit Lign Mat') ?></legend>
        <?php
            echo $this->Form->control('idm');
            echo $this->Form->control('id_profil');
            echo $this->Form->control('heur');
            echo $this->Form->control('jour');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
