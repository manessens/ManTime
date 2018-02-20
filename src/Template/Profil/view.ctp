<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Profil $profil
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Profil'), ['action' => 'edit', $profil->id_profil]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Profil'), ['action' => 'delete', $profil->id_profil], ['confirm' => __('Are you sure you want to delete # {0}?', $profil->id_profil)]) ?> </li>
        <li><?= $this->Html->link(__('List Profil'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Profil'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="profil view large-9 medium-8 columns content">
    <h3><?= h($profil->id_profil) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Nom Profil') ?></th>
            <td><?= h($profil->nom_profil) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id Profil') ?></th>
            <td><?= $this->Number->format($profil->id_profil) ?></td>
        </tr>
    </table>
</div>
