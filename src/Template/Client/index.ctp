<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Client[]|\Cake\Collection\CollectionInterface $client
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Client'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="client index large-9 medium-8 columns content">
    <h3><?= __('Client') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('idc') ?></th>
                <th scope="col"><?= $this->Paginator->sort('nom_client') ?></th>
                <th scope="col"><?= $this->Paginator->sort('prix') ?></th>
                <th scope="col"><?= $this->Paginator->sort('idm') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($client as $client): ?>
            <tr>
                <td><?= $this->Number->format($client->idc) ?></td>
                <td><?= h($client->nom_client) ?></td>
                <td><?= $this->Number->format($client->prix) ?></td>
                <td><?= $this->Number->format($client->idm) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $client->idc]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $client->idc]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $client->idc], ['confirm' => __('Are you sure you want to delete # {0}?', $client->idc)]) ?>
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
