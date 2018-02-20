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
                    <th class="col-xs-3" scope="col"><?= __('UO / Heure') ?></th>
                    <th class="col-xs-3" scope="col"><?= __('UO / Jour') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php pr($matrice);
                pr($matrice->lign_mat);
                exit; ?>
                <?php foreach ($matrice->lign_mat as $k => $ligne): ?>
                <tr>
                    <td><?= h($ligne->profil->nom_profil) ?> <?php echo $this->Form->hidden('lign_mat.'.$k.'.id_ligne'); ?></td>
                    <td><?php echo $this->Form->control('lign_mat.'.$k.'.heur'); ?></td>
                    <td><?php echo $this->Form->control('lign_mat.'.$k.'.jour'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
