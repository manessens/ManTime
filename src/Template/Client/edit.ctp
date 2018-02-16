<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Client $client
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $client->idc],
                ['confirm' => __('Are you sure you want to delete # {0}?', $client->idc)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Client'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="client form large-9 medium-8 columns content">
    <?= $this->Form->create($client) ?>
    <fieldset>
        <legend><?= __('Edit Client') ?></legend>
        <?php
            echo $this->Form->control('nom_client');
            echo $this->Form->control('prix');
            echo $this->Form->control('idm');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
