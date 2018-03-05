<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Activity $activity
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Activity'), ['action' => 'edit', $activity->ida]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Activity'), ['action' => 'delete', $activity->ida], ['confirm' => __('Are you sure you want to delete # {0}?', $activity->ida)]) ?> </li>
        <li><?= $this->Html->link(__('List Activities'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Activity'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="activities view large-10 large-10bis medium-8 columns content">
    <h3><?= h($activity->ida) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Ida') ?></th>
            <td><?= $this->Number->format($activity->ida) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Idp') ?></th>
            <td><?= $this->Number->format($activity->idp) ?></td>
        </tr>
    </table>
</div>
