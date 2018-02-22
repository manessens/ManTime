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
            pr($projet);exit;
            echo $this->Form->control('nom_projet');
        ?>
        <div class="input text required">
        <?php
            echo $this->Form->label('Client');
            echo $this->Form->select('idc', $clientOption);
        ?>
        </div>
        <?php echo $this->Form->control('date_debut', ['type' => 'text', 'label' => 'Date de début', 'class'=>'datepicker']); ?>
        <?php echo $this->Form->control('date_fin', ['type' => 'text', 'label' => 'Date de début', 'class'=>'datepicker']); ?>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer')) ?>
    <?= $this->Form->end() ?>
</div>

<?php echo $this->Html->script('ManTime/man.projet.js'); ?>
