<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Matrice $matrice
 */
?>
<div class="matrice view large-10 large-10bis medium-8 columns content">
    <legend><span class="text-danger"><?= h($matrice->nom_matrice) ?></span></legend>
    <div class="col-xs-12">
        <table class="col-xs-5 vertical-table">
            <thead>
                <tr>
                    <th scope="col"><?= __($matrice->nom_matrice) ?></th>
                    <th class="col-xs-2" scope="col"><?= __('UO / Heure') ?></th>
                    <th class="col-xs-2" scope="col"><?= __('UO / Jour') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($matrice->lign_mat)): ?>
                    <?php foreach ($matrice->lign_mat as $ligne): ?>
                    <tr>
                        <td><?= h($ligne->profil->nom_profil) ?></td>
                        <td><?= h($ligne->heur) ?></td>
                        <td><?= h($ligne->jour) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="col-xs-5 left">
        <div class="col-xs-5">
            <div class="col-xs-10 btn btn-danger"><?= $this->Form->postLink(__('Suppression'), ['action' => 'delete', $matrice->idm],
                    ['confirm' => __('Êtes-vous sûr de vouloir supprimer la matrice {0}?', $matrice->nom_matrice)]) ?></div>
        </div>
        <div class="btn right btn-warning"><?= $this->Html->link(__('Edition'), ['action' => 'edit', $matrice->idm]) ?></div>
    </div>
</div>
