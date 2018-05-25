<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Projet $projet
 */
?>
<div class="projet form large-10 large-10bis medium-8 columns content">
    <?= $this->Form->create($projet) ?>
    <fieldset>
        <legend><?= __('Ajouter un projet') ?></legend>
        <?php
            echo $this->Form->control('nom_projet');
        ?>
        <div class="input text required">
        <?php
            echo $this->Form->label('idc','Client');
            echo $this->Form->select('idc', $clientOption);
        ?>
        </div>
        <div class="input text required">
        <?php
            echo $this->Form->label('idf','Facturable');
            echo $this->Form->select('idf', $factOption);
        ?>
        </div>
        <div class="input text required">
        <?php
            echo $this->Form->label('Matrice');
            echo $this->Form->select('idm', $matricesOption, ['value'=>10]);
        ?>
        </div>
        <?php
            echo $this->Form->control('prix');
            echo $this->Form->control('date_debut', ['type' => 'text', 'label' => 'Date de début', 'class'=>'datepicker']);
            echo $this->Form->control('date_fin', ['type' => 'text', 'label' => 'Date de fin', 'class'=>'datepicker']);
        ?>
        <div class="input text col-xs-6">
        <?php
            echo $this->Form->label('participant','Participants');
            echo $this->Form->select('participant', $participants, ['multiple' => true , 'value' => $myParticipants, 'class' => 'multiple form-control']);
        ?>
            <div class="input-group">
                <input type="text" id='search_participant' class="form-control" placeholder="Search">
                <div class="input-group-btn">
                  <button class="btn btn-default height-input" type="button">
                    <b>X</b>
                  </button>
                </div>
            </div>
        </div>
        <div class="input text col-xs-6">
        <?php
            echo $this->Form->label('activities','Activités');
            echo $this->Form->select('activities', $activities, ['multiple' => true , 'value' => $myActivities, 'class' => 'multiple form-control']);
        ?>
            <div class="input-group">
                <input type="text" id='search_activit' class="form-control" placeholder="Search">
                <div class="input-group-btn">
                  <button class="btn btn-default height-input" type="button">
                    <b>X</b>
                  </button>
                </div>
            </div>
        </div>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer'), ['class' => 'btn btn-warning']) ?>
    <?= $this->Form->end() ?>
</div>

<?php echo $this->Html->script('ManTime/man.projet.js'); ?>
