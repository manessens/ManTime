<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>

<div class="users view large-10 large-10bis medium-8 columns content">
    <legend class="text-danger"><?= h($user->email) ?></legend>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Prenom') ?></th>
            <td><?= h($user->prenom) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Nom') ?></th>
            <td><?= h($user->nom) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Actif') ?></th>
            <td>
                <?php $this->set('test', $this->Number->format($user->admin) ) ?>
                <?= $this->element('tagYN') ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><?= __('Première connection') ?></th>
            <td>
                <?= $this->element('tagYN', ['test' => $this->Number->format($user->prem_connect)]) ?>
            </td>
        </tr>
        <tr>
            <th scope="row" class="text-danger"><?= __('Admin') ?></th>
            <td>
                <?= $this->element('tagYN', ['test' => $this->Number->format($user->role) >= 50]) ?>
            </td>
        </tr>
    </table>
    <div class="right">
        <div class="btn btn-warning"><?= $this->Html->link(__('Edition'), ['action' => 'edit', $user->idu]) ?></div>
    </div>
    <div class="related col-xs-6">
        <div class="col-xs-10 btn btn-danger"><?= $this->Form->postLink(__('Suppression'), ['action' => 'delete', $user->idu],
                ['confirm' => __('Êtes-vous sûr de vouloir supprimer le consultant {0}?', $user->email)]) ?></div>
    </div>

</div>
