<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Temp[]|\Cake\Collection\CollectionInterface $temps
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Temp'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="temps index large-9 medium-8 columns content">
    <h3><?= __('Temps') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('idt') ?></th>
                <th scope="col"><?= $this->Paginator->sort('date') ?></th>
                <th scope="col"><?= $this->Paginator->sort('time') ?></th>
                <th scope="col"><?= $this->Paginator->sort('idu') ?></th>
                <th scope="col"><?= $this->Paginator->sort('idp') ?></th>
                <th scope="col"><?= $this->Paginator->sort('id_profil') ?></th>
                <th scope="col"><?= $this->Paginator->sort('ida') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($temps as $temp): ?>
            <tr>
                <td><?= $this->Number->format($temp->idt) ?></td>
                <td><?= h($temp->date) ?></td>
                <td><?= $this->Number->format($temp->time) ?></td>
                <td><?= $this->Number->format($temp->idu) ?></td>
                <td><?= $this->Number->format($temp->idp) ?></td>
                <td><?= $this->Number->format($temp->id_profil) ?></td>
                <td><?= $this->Number->format($temp->ida) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $temp->idt]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $temp->idt]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $temp->idt], ['confirm' => __('Are you sure you want to delete # {0}?', $temp->idt)]) ?>
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
