<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Client $client
 */
?>
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
    <?= $this->Form->button(__('Enregistrer')) ?>
    <?= $this->Form->end() ?>
</div>
