<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Matrice[]|\Cake\Collection\CollectionInterface $matrice
 */
?>
<div class="matrice index large-9 medium-8 columns content">
    <h3><?= __('Matrice') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('idm') ?></th>
                <th scope="col"><?= $this->Paginator->sort('nom_matrice') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($matrice as $matrice): ?>
            <tr>
                <td><?= $this->Number->format($matrice->idm) ?></td>
                <td><?= h($matrice->nom_matrice) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $matrice->idm]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $matrice->idm]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $matrice->idm], ['confirm' => __('Are you sure you want to delete # {0}?', $matrice->idm)]) ?>
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
