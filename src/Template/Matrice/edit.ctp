<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Matrice $matrice
 */
?>
<div class="matrice form large-10 large-10bis medium-8 columns content">
    <?= $this->Form->create($matrice) ?>
    <fieldset>
        <legend><?= __('Edition de  la matrice') ?> <span class="text-danger"><?= h($matrice->nom_matrice) ?></span></legend>
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
                        <td><?php echo $this->Form->control('lign_mat.'.$k.'.heur', ['label' => false, 'disabled' => true]); ?></td>
                        <td><?php echo $this->Form->control('lign_mat.'.$k.'.jour', ['label' => false, 'disabled' => true]); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <button id='activate' class="btn left btn-danger" type="button">Activer édition</button>
    </fieldset>
    <div class="col-xs-5 left">
        <?= $this->Form->button(__('Enregistrer'), ['class'=>'btn btn-warning']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
<?php
    echo $this->Html->script('ManTime/man.modal.js');
    echo $this->Html->script('ManTime/man.matrice-admin.js');
?>
