<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Projet $projet
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Projet'), ['action' => 'edit', $projet->idp]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Projet'), ['action' => 'delete', $projet->idp], ['confirm' => __('Are you sure you want to delete # {0}?', $projet->idp)]) ?> </li>
        <li><?= $this->Html->link(__('List Projet'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Projet'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="projet view large-9 medium-8 columns content">
    <h3><?= h($projet->idp) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Idp') ?></th>
            <td><?= $this->Number->format($projet->idp) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Idc') ?></th>
            <td><?= $this->Number->format($projet->idc) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Date Debut') ?></th>
            <td><?= h($projet->date_debut) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Date Fin') ?></th>
            <td><?= h($projet->date_fin) ?></td>
        </tr>
    </table>
</div>
