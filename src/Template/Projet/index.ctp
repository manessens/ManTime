<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Projet[]|\Cake\Collection\CollectionInterface $projet
 */
?>
<div class="projet index large-9 medium-8 columns content">
    <h3><?= __('Projets') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('nom_projet','Projet') ?></th>
                <th scope="col"><?= $this->Paginator->sort('idc','Client') ?></th>
                <th class="date" scope="col"><?= $this->Paginator->sort('date_debut','Date de début') ?></th>
                <th class="date" scope="col"><?= $this->Paginator->sort('date_fin','Date de fin') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($projet as $projet): ?>
            <tr>
                <td><?= h($projet->nom_projet) ?></td>
                <td><?= h($projet->client->nom_client) ?></td>
                <td><?= h($projet->date_debut->i18nFormat('dd-MM-yyyy')) ?></td>
                <td><?= h($projet->date_fin->i18nFormat('dd-MM-yyyy')) ?></td>
                <td class="actions">
                    <?= $this->element( 'controle', ['id' =>$projet->idp, 'entity'=>$projet->nom_projet]); ?>
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
