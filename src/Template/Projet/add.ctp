<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Projet $projet
 */
?>
<div class="projet form large-9 medium-8 columns content">
    <?= $this->Form->create($projet) ?>
    <fieldset>
        <legend><?= __('Ajouter un projet') ?></legend>
        <?php
            echo $this->Form->control('nom_projet');
            echo $this->Form->select('idc', $clientOption, ['label' => 'test']);
            echo $this->Form->control('date_debut');
            echo $this->Form->control('date_fin');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer')) ?>
    <?= $this->Form->end() ?>
</div>
