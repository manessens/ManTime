<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Matrice $matrice
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $matrice->idm],
                ['confirm' => __('Are you sure you want to delete # {0}?', $matrice->idm)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Matrice'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<table class="col-xs-4 vertical-table">
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
<div class="col-xs-6 left">
    <div class="btn btn-danger"><?= $this->Html->link(__('Edition'), ['action' => 'edit', $matrice->idm]) ?></div>
</div>
<div class="matrice form large-9 medium-8 columns content">
    <?= $this->Form->create($matrice) ?>
    <fieldset>
        <legend><?= __('Edit Matrice') ?></legend>
        <?php
            echo $this->Form->control('nom_matrice');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
