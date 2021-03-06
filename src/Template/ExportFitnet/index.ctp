<?php
/**
 * @var \App\View\AppView $this
 */
?>
<div class="projet index large-10 large-10bis medium-8 columns content">
    <Legend><span class="text-danger"><?= __('Exports VSA') ?></span></legend>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th class="medium-1" scope="col"><?= $this->Paginator->sort('ExportFitnet.id_fit','#') ?></th>
                <th scope="col"><?= $this->Paginator->sort('ExportFitnet.date_debut','Du') ?></th>
                <th scope="col"><?= $this->Paginator->sort('ExportFitnet.date_fin','Au') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Client.nom_client','Client') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Users.prenom','Consultant') ?></th>
                <th scope="col"><?= $this->Paginator->sort('ExportFitnet.etat','Etat') ?></th>
                <th scope="col" class="actionsFit"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($exports as $export):?>
                <tr>
                    <td><?= h($export->id_fit) ?></td>
                    <td><?= h($export->date_debut->i18nFormat('dd-MM-yyyy')) ?></td>
                    <td><?= h($export->date_fin->i18nFormat('dd-MM-yyyy')) ?></td>
                    <td><?php echo count($export->clients) > 0?implode(',',$export->clients):'/' ; ?></td>
                    <td><?php echo count($export->users) > 0?implode(',',$export->users):'/' ; ?></td>
                    <td><?= $this->element('etatFitnet', ['etat'=>$export->etat]) ?></td>
                    <td class="actionsFit">
                        <?php if ($export->etat == \Cake\Core\Configure::read('vsa.wait')): ?>
                            <?= $this->element( 'controle', ['id' =>$export->id_fit, 'restrict'=>'%delete%']); ?>
                        <?php elseif($export->etat == \Cake\Core\Configure::read('vsa.end') || $export->etat == \Cake\Core\Configure::read('vsa.nerr')): ?>
                            <?= $this->element( 'controle', ['id' =>$export->id_fit, 'restrict'=>'%show%']); ?>
                        <?php elseif( $export->etat == \Cake\Core\Configure::read('vsa.err') ): ?>
                            <?= $this->element( 'controle', ['id' =>$export->id_fit, 'restrict'=>'%mod%']); ?>
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
