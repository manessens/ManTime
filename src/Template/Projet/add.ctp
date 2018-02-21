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
        ?>
        <div class="input text required">
        <?php
            echo $this->Form->label('Client');
            echo $this->Form->select('idc', $clientOption);
        ?>
        </div>
        <?php
            echo $this->Form->input('date_debut', ['type'=>'date', 'label' => 'Date de début']);
            echo $this->Form->input('date_fin', ['type'=>'date', 'label' => 'Date de fin']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer')) ?>
    <?= $this->Form->end() ?>
</div>
