<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>

<div class="users index large-10 large-10bis medium-8 columns content">
    <legend><?= __('Consultants') ?></legend>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('prenom','Prénom') ?></th>
                <th scope="col"><?= $this->Paginator->sort('nom','Nom') ?></th>
                <th scope="col"><?= $this->Paginator->sort('email','Adresse email') ?></th>
                <th class="medium-1" scope="col"><?= $this->Paginator->sort('actif','Actif') ?></th>
                <th class="medium-1" scope="col"><?= $this->Paginator->sort('admin','Admin') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= h($user->prenom) ?></td>
                <td><?= h($user->nom) ?></td>
                <td><?= h($user->email) ?></td>
                <td>
                    <?= $this->element('tagYN', ['test' => $this->Number->format($user->actif)]) ?>
                </td>
                <td>
                    <?= $this->element('tagYN', ['test' => $this->Number->format($user->admin)]) ?>
                </td>
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
