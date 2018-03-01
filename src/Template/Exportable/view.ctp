<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Exportable $exportable
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Exportable'), ['action' => 'edit', $exportable->ide]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Exportable'), ['action' => 'delete', $exportable->ide], ['confirm' => __('Are you sure you want to delete # {0}?', $exportable->ide)]) ?> </li>
        <li><?= $this->Html->link(__('List Exportable'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Exportable'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="exportable view large-9 medium-8 columns content">
    <h3><?= h($exportable->ide) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Ide') ?></th>
            <td><?= $this->Number->format($exportable->ide) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('N Sem') ?></th>
            <td><?= $this->Number->format($exportable->n_sem) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Annee') ?></th>
            <td><?= $this->Number->format($exportable->annee) ?></td>
        </tr>
    </table>
</div>
