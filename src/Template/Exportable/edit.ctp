<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Exportable $exportable
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $exportable->ide],
                ['confirm' => __('Are you sure you want to delete # {0}?', $exportable->ide)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Exportable'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="exportable form large-9 medium-8 columns content">
    <?= $this->Form->create($exportable) ?>
    <fieldset>
        <legend><?= __('Edit Exportable') ?></legend>
        <?php
            echo $this->Form->control('n_sem');
            echo $this->Form->control('annee');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
