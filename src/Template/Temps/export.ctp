<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Temp[]|\Cake\Collection\CollectionInterface $temps
 */
?>
<div class="col-xs-12 new_content content">
    <?= $this->Form->create($export, ['url'=> ['controller' => 'Temps', 'action' => 'export']]) ?>
    <fieldset>
        <legend><?= __('Export') ?><span id="import_export" data-target="import" class="right btn btn-default">&#8633;</span></legend>

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
            if($this->request->session()->read('Auth.User.role') >= \Cake\Core\Configure::read('role.admin')){
                echo $this->Form->select('user', [1=>'test'], ['label' => 'Consultant']);
            }else{
                echo $this->Form->control('user', ['label' => 'Consultant', 'empty' => '-']);
            }
        ?>
        </div>
        <div class="input col-xs-6 left">
        <?php
            echo $this->Form->control('fitnet', ['type' => 'checkbox', 'label'=>'Export avec niveau de détail au jour']);
        ?>
        </div>
        <div class="col-xs-6">
            <div class="right control_export">
                <?= $this->Form->button(__('Export local'), ['class'=>'left btn btn-info']) ?>
                <?php if($this->request->session()->read('Auth.User.role') >= \Cake\Core\Configure::read('role.admin')): ?>
                    <?= $this->Form->button(__('Export Fitnet'), ['type'=>'button', 'class'=>'right btn btn-warning', 'id'=>'export_fitnet']) ?>
                <?php endif; ?>
            </div>
        </div>
    </fieldset>
    <?= $this->Form->end() ?>
</div>

<?php echo $this->Html->script('ManTime/man.export.js'); ?>
