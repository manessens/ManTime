<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Client $client
 */
?>
<div class="client form large-9 medium-8 columns content">
    <?= $this->Form->create($client) ?>
    <fieldset>
        <legend><?= __('Edition du client') ?><span class="text-danger"><?= h($client->nom_client) ?></span></legend>
        <?php
            echo $this->Form->control('nom_client');
            echo $this->Form->control('prix');
            echo $this->Form->select('idm', $matricesOption, ['value' => $client->idm ]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer'), ['class' => 'btn btn-warning']) ?>
    <?= $this->Form->end() ?>
</div>
