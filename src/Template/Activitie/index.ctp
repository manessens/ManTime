<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Activitie[]|\Cake\Collection\CollectionInterface $activitie
 */
?>
<div class="activitie index large-9 medium-8 columns content">
    <h3><?= __('Activitie') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('nom_activit') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($activitie as $activitie): ?>
            <tr>
                <td><?= h($activitie->nom_activit) ?></td>
                <td class="actions">
                    <?= $this->element( 'controle', ['id' =>$activitie->ida, 'entity'=>$activitie->nom_activit]); ?>
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
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
