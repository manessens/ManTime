<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Temp[]|\Cake\Collection\CollectionInterface $temps
 */
?>
<div class="temps index large-10 large-10bis medium-8 columns content">
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Export') ?></legend>

        <?php
            echo $this->Form->control('date_debut', ['type' => 'text', 'label' => 'Date de début', 'class'=>'datepicker']);
            echo $this->Form->control('date_fin', ['type' => 'text', 'label' => 'Date de fin', 'class'=>'datepicker']);
        ?>

        <div class="input text col-xs-6">
        <?php
            echo $this->Form->label('client','Client');
            echo $this->Form->select('client', $clients, ['empty' => '-']);
        ?>
        </div>

        <div class="input text col-xs-6">
        <?php
            echo $this->Form->label('user','Consultant');
            echo $this->Form->select('user', $users, ['empty' => '-']);
        ?>
        </div>
    </fieldset>
    <?= $this->Form->button(__('Exporter'), ['class'=>'right btn btn-warning']) ?>
    <?= $this->Form->end() ?>
</div>

<?php echo $this->Html->script('ManTime/man.export.js'); ?>
