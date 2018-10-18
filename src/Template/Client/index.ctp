<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Client[]|\Cake\Collection\CollectionInterface $client
 */
?>
<div class="client index large-10 large-10bis medium-8 columns content">
    <legend><span class="text-danger"><?= __('Clients') ?></span></legend>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('Client.nom_client','Client') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Agence.nom_agence','Agence') ?></th>
                <th class="medium-1" scope="col"><?= $this->Paginator->sort('Client.id_fit','Fitnet') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($client as $client): ?>
            <tr>
                <td><?= h($client->nom_client) ?></td>
                <td><?= h($client->agence->nom_agence) ?></td>
                <td><?= $this->element('link2fitnet', ['idf'=>$client->id_fit]) ?></td>
                <td class="actions">
                    <?= $this->element( 'controle', ['id' =>$client->idc, 'entity'=>$client->nom_client]); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('premier')) ?>
            <?= $this->Paginator->prev('< ' . __('précédent')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('suivant') . ' >') ?>
            <?= $this->Paginator->last(__('dernier') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} de {{pages}}, affiché {{current}} enregistrement(s) sur {{count}} total')]) ?></p>
    </div>
</div>
