<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Projet[]|\Cake\Collection\CollectionInterface $projet
 */
?>
<div class="projet index large-10 large-10bis medium-8 columns content">
    <legend><span class="text-danger"><?= __('Projets') ?></span></legend>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('Client.nom_client','Client') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Projet.nom_projet','Projet') ?></th>
                <th class="medium-1" scope="col"><?= $this->Paginator->sort('Projet.id_fit','VSA') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Facturable.nom_fact','Facturable') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Matrice.nom_matrice','Matrice') ?></th>
                <th class="medium-1" scope="col"><?= $this->Paginator->sort('Projet.prix','prix UO (€)') ?></th>
                <th class="date" scope="col"><?= $this->Paginator->sort('Projet.date_debut','Date de début') ?></th>
                <th class="date" scope="col"><?= $this->Paginator->sort('Projet.date_fin','Date de fin') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($projet as $projet): ?>
            <tr>
                <td><?= h($projet->client->nom_client) ?></td>
                <td><?= h($projet->projname) ?></td>
                <td><?= $this->element('link2fitnet', ['idf'=>$projet->id_fit]) ?></td>
                <td><?= h($projet->facturable->nom_fact) ?></td>
                <td><?= h($projet->matrice->nom_matrice) ?></td>
                <td><?= $this->Number->format($projet->prix) ?></td>
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
