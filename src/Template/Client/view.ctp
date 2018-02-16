<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Client $client
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Client'), ['action' => 'edit', $client->idc]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Client'), ['action' => 'delete', $client->idc], ['confirm' => __('Are you sure you want to delete # {0}?', $client->idc)]) ?> </li>
        <li><?= $this->Html->link(__('List Client'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Client'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="client view large-9 medium-8 columns content">
    <h3><?= h($client->idc) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Nom Client') ?></th>
            <td><?= h($client->nom_client) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Idc') ?></th>
            <td><?= $this->Number->format($client->idc) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Prix') ?></th>
            <td><?= $this->Number->format($client->prix) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Idm') ?></th>
            <td><?= $this->Number->format($client->idm) ?></td>
        </tr>
    </table>
</div>
