<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Matrice $matrice
 */
?>
<div class="matrice form large-10 large-10bis medium-8 columns content">
    <?= $this->Form->create($matrice) ?>
    <fieldset>
        <legend><?= __('Edition de  Matrice') ?></legend>
        <table class="col-xs-5 vertical-table">
            <thead>
                <tr>
                    <th scope="col"><?php echo $this->Form->control('nom_matrice'); ?></th>
                    <th class="col-xs-3" scope="col"><?= __('UO / Heure') ?></th>
                    <th class="col-xs-3" scope="col"><?= __('UO / Jour') ?></th>
                    <th class="col-xs-3" style="width: 0px" scope="col"><?= __('ID') ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= h('Technique ABAP') ?> <?php echo $this->Form->hidden('lign_mat.0.id_ligne'); ?></td>
                    <td><?php echo $this->Form->control('lign_mat.0.heur', ['label' => false]); ?></td>
                    <td><?php echo $this->Form->control('lign_mat.0.jour', ['label' => false]); ?></td>
                    <td><?php echo $this->Form->control('lign_mat.0.id_profil', ['value' => 1]); ?></td>
                </tr>
                <tr>
                    <td><?= h('Technicien ABAP Expert') ?> <?php echo $this->Form->hidden('lign_mat.1.id_ligne'); ?></td>
                    <td><?php echo $this->Form->control('lign_mat.1.heur', ['label' => false]); ?></td>
                    <td><?php echo $this->Form->control('lign_mat.1.jour', ['label' => false]); ?></td>
                    <td><?php echo $this->Form->control('lign_mat.1.id_profil', ['value' => 2]); ?></td>
                </tr>
                <tr>
                    <td><?= h('Fonctionnel Expert') ?> <?php echo $this->Form->hidden('lign_mat.2.id_ligne'); ?></td>
                    <td><?php echo $this->Form->control('lign_mat.2.heur', ['label' => false]); ?></td>
                    <td><?php echo $this->Form->control('lign_mat.2.jour', ['label' => false]); ?></td>
                    <td><?php echo $this->Form->control('lign_mat.2.id_profil', ['value' => 3]); ?></td>
                </tr>
                <tr>
                    <td><?= h('Fonctionnel BI/BC') ?> <?php echo $this->Form->hidden('lign_mat.3.id_ligne'); ?></td>
                    <td><?php echo $this->Form->control('lign_mat.3.heur', ['label' => false]); ?></td>
                    <td><?php echo $this->Form->control('lign_mat.3.jour', ['label' => false]); ?></td>
                    <td><?php echo $this->Form->control('lign_mat.3.id_profil', ['value' => 3]); ?></td>
                </tr>
                <tr>
                    <td><?= h('Expert / CP / Référent') ?> <?php echo $this->Form->hidden('lign_mat.4.id_ligne'); ?></td>
                    <td><?php echo $this->Form->control('lign_mat.4.heur', ['label' => false]); ?></td>
                    <td><?php echo $this->Form->control('lign_mat.4.jour', ['label' => false]); ?></td>
                    <td><?php echo $this->Form->control('lign_mat.4.id_profil', ['value' => 4]); ?></td>
                </tr>
            </tbody>
        </table>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer'), ['class' => 'btn btn-warning']) ?>
    <?= $this->Form->end() ?>
</div>
