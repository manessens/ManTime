<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Projet[]|\Cake\Collection\CollectionInterface $projet
 */
?>
<div class="projet index large-10 large-10bis medium-8 columns content">
    <h3><?= __('Exports Fitnet') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('ExportFitnet.date_debut','Du') ?></th>
                <th scope="col"><?= $this->Paginator->sort('ExportFitnet.date_fin','Au') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Client.nom_client','Client') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Users.prenom','Consultant') ?></th>
                <th scope="col"><?= $this->Paginator->sort('ExportFitnet.etat','Etat') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($exports as $export):?>
                <tr>
                    <td><?= h($export->date_debut) ?></td>
                    <td><?= h($export->date_fin) ?></td>
                    <td><?php echo $export->client != null?$export->client->nom_client:'/' ; ?></td>
                    <td><?= h($export->users->fullname) ?></td>
                    <td><?= $this->element('etatFitnet', ['etat'=>$export->etat]) ?></td>
                    <td class="actions">
                        <?php if ($export->etat == 'En attente'): ?>
                            <?= $this->element( 'controle', ['id' =>$export->id_fit, 'restrict'=>'%delete%']); ?>
                        <?php elseif($export->etat == 'En cours' || $export->etat == 'Terminé' || $export->etat == 'En erreur'): ?>
                            <?= $this->element( 'controle', ['id' =>$export->id_fit, 'restrict'=>'%show%']); ?>
                        <?php endif; ?>
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
