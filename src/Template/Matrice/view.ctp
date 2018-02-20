<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Matrice $matrice
 */
?>
<div class="matrice view large-9 medium-8 columns content">
    <h3><?= h($matrice->nom_matrice) ?></h3>
    <table class="vertical-table">
        <thead>
            <tr>
                <th scope="col"><?= __($matrice->nom_matrice) ?></th>
                <th scope="col"><?= __('UO / Heure') ?></th>
                <th scope="col"><?= __('UO / Jour') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($matrice->lign_mat)): ?>
                <?php foreach ($matrice->lignes as $ligne): ?>
                <tr>
                    <td><?= h($ligne->profil) ?></td>
                    <td><?= h($ligne->heur) ?></td>
                    <td><?= h($ligne->jour) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="right">
        <div class="btn btn-danger"><?= $this->Html->link(__('Edition'), ['action' => 'edit', $matrice->idm]) ?></div>
    </div>
</div>
