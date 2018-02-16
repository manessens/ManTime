<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>

<div class="users index large-9 medium-8 columns content">
    <legend><?= __('Consultants') ?></legend>
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
                    <?= $this->element( 'controle', ['id' => $user->idu, 'entity'=>$user->email]); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('premier')) ?>
            <?= $this->Paginator->prev('< ' . __('précédent')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('suivant') . ' >') ?>
            <?= $this->Paginator->last(__('dernier') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} de {{pages}}, affiché {{current}} enregistrement(s) sur {{count}} total')]) ?></p>
    </div>
</div>
