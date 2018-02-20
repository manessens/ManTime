<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Temp $temp
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Temp'), ['action' => 'edit', $temp->idt]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Temp'), ['action' => 'delete', $temp->idt], ['confirm' => __('Are you sure you want to delete # {0}?', $temp->idt)]) ?> </li>
        <li><?= $this->Html->link(__('List Temps'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Temp'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="temps view large-9 medium-8 columns content">
    <h3><?= h($temp->idt) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Idt') ?></th>
            <td><?= $this->Number->format($temp->idt) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Time') ?></th>
            <td><?= $this->Number->format($temp->time) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Idu') ?></th>
            <td><?= $this->Number->format($temp->idu) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Idp') ?></th>
            <td><?= $this->Number->format($temp->idp) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id Profil') ?></th>
            <td><?= $this->Number->format($temp->id_profil) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ida') ?></th>
            <td><?= $this->Number->format($temp->ida) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Date') ?></th>
            <td><?= h($temp->date) ?></td>
        </tr>
    </table>
</div>
