<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Exportable[]|\Cake\Collection\CollectionInterface $exportable
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Exportable'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="exportable index large-9 medium-8 columns content">
    <h3><?= __('Exportable') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('ide') ?></th>
                <th scope="col"><?= $this->Paginator->sort('n_sem') ?></th>
                <th scope="col"><?= $this->Paginator->sort('annee') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($exportable as $exportable): ?>
            <tr>
                <td><?= $this->Number->format($exportable->ide) ?></td>
                <td><?= $this->Number->format($exportable->n_sem) ?></td>
                <td><?= $this->Number->format($exportable->annee) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $exportable->ide]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $exportable->ide]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $exportable->ide], ['confirm' => __('Are you sure you want to delete # {0}?', $exportable->ide)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
