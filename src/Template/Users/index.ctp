<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>

<div class="users index large-9 medium-8 columns content">
    <h3><?= __('Consultants') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('prenom') ?></th>
                <th scope="col"><?= $this->Paginator->sort('nom') ?></th>
                <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                <!-- <th scope="col"><?= $this->Paginator->sort('mdp') ?></th> -->
                <th scope="col medium-1"><?= $this->Paginator->sort('actif') ?></th>
                <th scope="col medium-1"><?= $this->Paginator->sort('admin') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= h($user->prenom) ?></td>
                <td><?= h($user->nom) ?></td>
                <td><?= h($user->email) ?></td>
                <!-- <td><?= h($user->mdp) ?></td> -->
                <td><?= $this->Number->format($user->actif) ?></td>
                <td><?= $this->Number->format($user->admin) ?></td>
                <td class="actions">
                    <div class="btn-group">
                        <div class="btn-info"><?= $this->Html->link(__('View'), ['action' => 'view', $user->idu]) ?></div>
                        <div class="btn-warining"><?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->idu]) ?></div>
                        <div class="btn-danger"><?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $user->idu],
                                ['confirm' => __('Êtes vous sûr de supprimer # {0}?', $user->email)]) ?></div>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('précédent')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('suivant') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
