<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Projet $projet
 */
?>
<div class="projet form large-10 medium-8 columns content">
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
        <?php echo $this->Form->control('date_debut', ['type' => 'text', 'label' => 'Date de début', 'class'=>'datepicker']); ?>
        <?php echo $this->Form->control('date_fin', ['type' => 'text', 'label' => 'Date de début', 'class'=>'datepicker']); ?>
        <div class="input text col-xs-6">
        <?php
            echo $this->Form->label('participant','Participants');
            echo $this->Form->select('participant', $particpants, ['multiple' => true , 'value' => $myParticpants, 'class' => 'multiple form-control']);
        ?>
        </div>
        <div class="input text col-xs-6">
        <?php
            echo $this->Form->label('activities','Activités');
            echo $this->Form->select('activities', $activities, ['multiple' => true , 'value' => $myActivities, 'class' => 'multiple form-control']);
        ?>
        </div>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer'), ['class' => 'btn btn-warning']) ?>
    <?= $this->Form->end() ?>
</div>

<?php echo $this->Html->script('ManTime/man.projet.js'); ?>
