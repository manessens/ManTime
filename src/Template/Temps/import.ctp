<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Temp[]|\Cake\Collection\CollectionInterface $temps
 */
?>
<div class="col-xs-12 new_content content">
    <?= $this->Form->create($import, ['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Import') ?><span id="export" class="right btn btn-info">&#8633;</span></legend>

        <?php
            echo $this->Form->file('fileimport');
        ?>

        <?= $this->Form->button(__('Importer'), ['class'=>'right btn btn-warning']) ?>
    </fieldset>
    <?= $this->Form->end() ?>
    <?php
        if (!empty($arrayUserRefused) || !empty($arrayClientRefused) || !empty($arrayProjetRefused)) {
            echo 'Consultants';
            pr($arrayUserRefused);
            echo 'Clients';
            pr($arrayClientRefused);
            echo 'Projets';
            pr($arrayProjetRefused);
            echo 'Profils';
            pr($arrayProfilRefused);
            echo 'Activités';
            pr($arrayActivitieRefused);
        }
     ?>
</div>

<?php echo $this->Html->script('ManTime/man.export.js'); ?>
