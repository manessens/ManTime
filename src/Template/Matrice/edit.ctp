<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Matrice $matrice
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $matrice->idm],
                ['confirm' => __('Are you sure you want to delete # {0}?', $matrice->idm)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Matrice'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="matrice form large-9 medium-8 columns content">
    <?= $this->Form->create($matrice) ?>
    <fieldset>
        <legend><?= __('Edit Matrice') ?></legend>
        <?php
            echo $this->Form->control('nom_matrice');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
