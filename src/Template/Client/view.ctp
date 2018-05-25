<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Client $client
 */
?>
<div class="client view large-10 large-10bis medium-8 columns content">
    <h3><?= h($client->nom_client) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Nom Client') ?></th>
            <td><?= h($client->nom_client) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Agence') ?></th>
            <td><?= h($client->agence->nom_agence) ?></td>
        </tr>
    </table>
    <div class="right">
        <div class="btn btn-warning"><?= $this->Html->link(__('Edition'), ['action' => 'edit', $client->idc]) ?></div>
    </div>
    <div class="related col-xs-6">
        <div class="col-xs-10 btn btn-danger"><?= $this->Form->postLink(__('Suppression'), ['action' => 'delete', $client->idc],
                ['confirm' => __('Êtes-vous sûr de vouloir supprimer le client {0}?', $client->nom_client)]) ?></div>
    </div>
</div>
