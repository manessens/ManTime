<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\LignMat $lignMat
 */
?>
<?php pr($lignMat);exit; ?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Lign Mat'), ['action' => 'edit', $lignMat->id_ligne]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Lign Mat'), ['action' => 'delete', $lignMat->id_ligne], ['confirm' => __('Are you sure you want to delete # {0}?', $lignMat->id_ligne)]) ?> </li>
        <li><?= $this->Html->link(__('List Lign Mat'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Lign Mat'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="lignMat view large-9 medium-8 columns content">
    <h3><?= h($lignMat->id_ligne) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Id Ligne') ?></th>
            <td><?= $this->Number->format($lignMat->id_ligne) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Idm') ?></th>
            <td><?= $this->Number->format($lignMat->idm) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id Profil') ?></th>
            <td><?= $this->Number->format($lignMat->id_profil) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Heur') ?></th>
            <td><?= $this->Number->format($lignMat->heur) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Jour') ?></th>
            <td><?= $this->Number->format($lignMat->jour) ?></td>
        </tr>
    </table>
</div>
