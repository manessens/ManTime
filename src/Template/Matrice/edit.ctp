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
        <table class="col-xs-5 vertical-table">
            <thead>
                <tr>
                    <th scope="col"><?php echo $this->Form->control('nom_matrice'); ?></th>
                    <th class="col-xs-3" scope="col"><?= __('UO / Heure') ?></th>
                    <th class="col-xs-3" scope="col"><?= __('UO / Jour') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($matrice->lign_mat)): ?>
                    <?php foreach ($matrice->lign_mat as $k => $ligne): ?>
                    <tr>
                        <td><?= h($ligne->profil->nom_profil) ?> <?php echo $this->Form->hidden('lign_mat.'.$k.'.id_ligne'); ?></td>
                        <td><?php echo $this->Form->control('lign_mat.'.$k.'.heur', ['label' => false]); ?></td>
                        <td><?php echo $this->Form->control('lign_mat.'.$k.'.jour', ['label' => false]); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer'), ['class'=>'btn btn-warning', 'confirm'=>__("La matrice est peut-être déjà utilisé par d'autres clients.
    Êtes vous sûr de vouloir effectuer des modifications sur {0} ?", $matrice->nom_matrice)]) ?>
    <?= $this->Form->end() ?>
</div>
