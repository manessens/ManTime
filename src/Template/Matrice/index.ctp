<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Matrice[]|\Cake\Collection\CollectionInterface $matrice
 */
?>
<div class="matrice index large-10 large-10bis medium-8 columns content">
    <h3><?= __('Matrice') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('nom_matrice','Matrice') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($matrice as $matrice): ?>
            <tr>
                <td><?= h($matrice->nom_matrice) ?></td>
                <td class="actions">
                    <?= $this->element( 'controle', ['id' =>$matrice->idm, 'entity'=>$matrice->nom_matrice]); ?>
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
