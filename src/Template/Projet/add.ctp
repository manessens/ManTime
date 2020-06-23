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
        <div class="input text required">
        <?php
            echo $this->Form->label('idc','Client');
            echo $this->Form->select('idc', $clientOption, ['id'=>'id_client']);
        ?>
        </div>
        <!-- FITNET -->
        <div class="col-xs-12">
            <div class="left">
                <?php echo $this->Form->control('id_fit', ['readonly', "type" => 'text', 'label' => ['text'=>'Id VSA']]); ?>
            </div>
            <div class="col-xs-6">
                <?php echo $this->Form->label('select_fit','Liste VSA'); ?>
                <select name="select_fit" type="text" id="liste_vsa"></select>
            </div>
            <div class="left control_fitnet">
                <button type="button" id="linker" class="btn <?php echo $projet->id_fit>0?"btn-success":"btn-primary"; ?>"
                     data-toggle="modal" data-target="#linkModal" data-whatever="">Actualiser liste VSA</button>
                <button type="button" id="resetter" data-value="<?php echo $projet->id_fit ?>" class="btn btn-danger">Supprimer Id</button>
                <div class="loader btn" style="display:none;" id="loader"> </div>
            </div>
         </div>
         <!-- /FITNET/ -->
         <?php
            echo $this->Form->control('nom_projet');
            echo $this->Form->label('idf','Facturable');
            echo $this->Form->select('idf', $factOption);
            echo $this->Form->control('date_debut', ['type' => 'text', 'label' => 'Date de début', 'class'=>'datepicker']);
            echo $this->Form->control('date_fin', ['type' => 'text', 'label' => 'Date de fin', 'class'=>'datepicker']);
         ?>
        <div class="input text required">
            <?php
                echo $this->Form->label('idm', 'Matrice');
                echo $this->Form->select('idm', $matricesOption, ['value'=>10]);
            ?>
        </div>
        <?php
            echo $this->Form->control('prix');
        ?>
        <div class="input text required">
        <?php
            echo $this->Form->label('idu', 'CP référent');
            echo $this->Form->select('idu', $referentOption);
        ?>
        </div>
        <div class="input text col-xs-6">
            <?php
                echo $this->Form->label('participant','Participants');
                echo $this->Form->select('participant', $participants, ['multiple' => true , 'value' => $myParticipants, 'class' => 'multiple form-control']);
            ?>
            <div class="input-group">
                <input type="text" id='search_participant' class="form-control" placeholder="Search">
                <div class="input-group-btn">
                  <button class="btn btn-default height-input" type="button"><b>X</b></button>
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
                  <button class="btn btn-default height-input" type="button"><b>X</b></button>
                </div>
            </div>
        </div>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer'), ['class' => 'btn btn-warning']) ?>
    <?= $this->Form->end() ?>
</div>

<!-- addtional style and script for this page only -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<?php echo $this->Html->script('ManTime/man.projet.js'); ?>
<?php echo $this->Html->css('ManTime/man.loader.css'); ?>
<?php echo $this->Html->css('ManTime/man.projet.css'); ?>
