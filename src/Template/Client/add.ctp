<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Client $client
 */
?>
<div class="client form large-10 large-10bis medium-8 columns content">
    <?= $this->Form->create($client) ?>
    <fieldset>
        <legend><?= __('Ajouter un client') ?></legend>
        <?php
            echo $this->Form->control('nom_client');
        ?>
        <div class="input text required">
        <?php
            echo $this->Form->label('id_agence','Agence');
            echo $this->Form->select('id_agence', $agenceOption);
        ?>
        </div>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer'), ['class' => 'btn btn-warning']) ?>
    <?= $this->Form->end() ?>
</div>
