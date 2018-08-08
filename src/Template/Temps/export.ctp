<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Temp[]|\Cake\Collection\CollectionInterface $temps
 */
?>
<div class="col-xs-12 new_content content">
    <?= $this->Form->create($export) ?>
    <fieldset>
        <legend><?= __('Export') ?></legend>

        <?php
            echo $this->Form->control('date_debut', ['type' => 'text', 'label' => 'Date de début', 'class'=>'datepicker']);
            echo $this->Form->control('date_fin', ['type' => 'text', 'label' => 'Date de fin', 'class'=>'datepicker']);
        ?>

        <div class="input text col-xs-6">
        <?php
            echo $this->Form->control('client',  ['label' => 'Client', 'empty' => '-']);
        ?>
        </div>

        <div class="input text col-xs-6">
        <?php
            echo $this->Form->control('user', ['label' => 'Consultant', 'empty' => '-']);
        ?>
        </div>
        <div class="input col-xs-6 left">
        <?php
            echo $this->Form->control('fitnet', ['type' => 'checkbox', 'label'=>'Export avec niveau de détail au jour']);
        ?>
        </div>
        <div class="right control_export">
            <?= $this->Form->button(__('Export local'), ['class'=>'left btn btn-info']) ?>
            <?= $this->Form->button(__('Export Fitnet'), ['type'=>'button', 'class'=>'right btn btn-warning']) ?>
        </div>
    </fieldset>
    <?= $this->Form->end() ?>
</div>

<?php echo $this->Html->script('ManTime/man.export.js'); ?>
