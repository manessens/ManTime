<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LignMat[]|\Cake\Collection\CollectionInterface $lignMat
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Lign Mat'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="lignMat index large-9 medium-8 columns content">
    <h3><?= __('Lign Mat') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id_ligne') ?></th>
                <th scope="col"><?= $this->Paginator->sort('idm') ?></th>
                <th scope="col"><?= $this->Paginator->sort('id_profil') ?></th>
                <th scope="col"><?= $this->Paginator->sort('heur') ?></th>
                <th scope="col"><?= $this->Paginator->sort('jour') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lignMat as $lignMat): ?>
            <tr>
                <td><?= $this->Number->format($lignMat->id_ligne) ?></td>
                <td><?= $this->Number->format($lignMat->idm) ?></td>
                <td><?= $this->Number->format($lignMat->id_profil) ?></td>
                <td><?= $this->Number->format($lignMat->heur) ?></td>
                <td><?= $this->Number->format($lignMat->jour) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $lignMat->id_ligne]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $lignMat->id_ligne]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $lignMat->id_ligne], ['confirm' => __('Are you sure you want to delete # {0}?', $lignMat->id_ligne)]) ?>
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
