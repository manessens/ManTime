<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Activitie $activitie
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Activitie'), ['action' => 'edit', $activitie->ida]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Activitie'), ['action' => 'delete', $activitie->ida], ['confirm' => __('Are you sure you want to delete # {0}?', $activitie->ida)]) ?> </li>
        <li><?= $this->Html->link(__('List Activitie'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Activitie'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="activitie view large-9 medium-8 columns content">
    <h3><?= h($activitie->ida) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Nom Activit') ?></th>
            <td><?= h($activitie->nom_activit) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Ida') ?></th>
            <td><?= $this->Number->format($activitie->ida) ?></td>
        </tr>
    </table>
</div>
