<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Matrice $matrice
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Matrice'), ['action' => 'edit', $matrice->idm]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Matrice'), ['action' => 'delete', $matrice->idm], ['confirm' => __('Are you sure you want to delete # {0}?', $matrice->idm)]) ?> </li>
        <li><?= $this->Html->link(__('List Matrice'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Matrice'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="matrice view large-9 medium-8 columns content">
    <h3><?= h($matrice->idm) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Nom Matrice') ?></th>
            <td><?= h($matrice->nom_matrice) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Idm') ?></th>
            <td><?= $this->Number->format($matrice->idm) ?></td>
        </tr>
    </table>
</div>
