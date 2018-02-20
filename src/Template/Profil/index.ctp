<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Profil[]|\Cake\Collection\CollectionInterface $profil
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Profil'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="profil index large-9 medium-8 columns content">
    <h3><?= __('Profil') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id_profil') ?></th>
                <th scope="col"><?= $this->Paginator->sort('nom_profil') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($profil as $profil): ?>
            <tr>
                <td><?= $this->Number->format($profil->id_profil) ?></td>
                <td><?= h($profil->nom_profil) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $profil->id_profil]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $profil->id_profil]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $profil->id_profil], ['confirm' => __('Are you sure you want to delete # {0}?', $profil->id_profil)]) ?>
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
