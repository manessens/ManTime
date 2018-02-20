<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Matrice $matrice
 */
?>
<div class="matrice form large-9 medium-8 columns content">
    <?= $this->Form->create($matrice) ?>
    <fieldset>
        <legend><?= __('Edition de  Matrice') ?></legend>
        <table class="col-xs-4 vertical-table">
            <thead>
                <tr>
                    <th scope="col"><?php echo $this->Form->control('nom_matrice'); ?></th>
                    <th class="col-xs-2" scope="col"><?= __('UO / Heure') ?></th>
                    <th class="col-xs-2" scope="col"><?= __('UO / Jour') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($matrice->lign_mat)): ?>
                    <?php foreach ($matrice->lign_mat as $ligne): ?>
                    <tr>
                        <td><?= h($ligne->profil->nom_profil) ?></td>
                        <td><?php echo $this->Form->control('nom_matrice.'.$ligne->id_ligne.'.heur'); ?></td>
                        <td><?php echo $this->Form->control('nom_matrice.'.$ligne->id_ligne.'.jour'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
