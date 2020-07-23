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

        <!-- Clients -->
        <!-- <div class="input text col-xs-6">
        <?php // echo $this->Form->control('client',  ['label' => 'Client', 'empty' => '-']); ?>
        </div> -->
        <div class="input text col-xs-6">
        <?php
           echo $this->Form->label('client','Clients');
           echo $this->Form->select('client', $clients, ['multiple' => true , 'class' => 'multiple form-control']);
        ?>
           <div class="input-group">
               <input type="text" id='search_client' class="form-control" placeholder="Search">
               <div class="input-group-btn">
                 <button class="btn btn-default height-input" type="button"><b>X</b></button>
               </div>
           </div>
        </div>
        <!-- Users -->
        <div class="input text col-xs-6">
        <?php
            // @TODO : ajuster le select user + filtrer le user Client?
            if($this->request->session()->read('Auth.User.role') >= \Cake\Core\Configure::read('role.cp')){
                // echo $this->Form->control('user', ['label' => 'Consultant', 'empty' => '-']);
               echo $this->Form->label('user','Consultants');
               echo $this->Form->select('user', $users, ['multiple' => true , 'class' => 'multiple form-control']);
            }else{
                echo $this->Form->label('user','Consultant');
                echo $this->Form->select('user', [
                    $this->request->session()->read('Auth.User.idu')=>ucfirst($this->request->session()->read('Auth.User.prenom'))
                .' '.strtoupper($this->request->session()->read('Auth.User.nom'))], ['label' => 'Consultant']);
            }
        ?>
        <?php if($this->request->session()->read('Auth.User.role') >= \Cake\Core\Configure::read('role.cp')): ?>
           <div class="input-group">
               <input type="text" id='search_user' class="form-control" placeholder="Search">
               <div class="input-group-btn">
                 <button class="btn btn-default height-input" type="button"><b>X</b></button>
               </div>
           </div>
        <?php endif; ?>
        </div>

    </fieldset>
    <div class="input col-xs-6 left">
    <?php
        echo $this->Form->control('fitnet', ['type' => 'checkbox', 'label'=>'Export avec niveau de détail au jour']);
    ?>
    </div>
    <div class="col-xs-6">
        <div class="right control_export">
            <?= $this->Form->button(__('Export local'), ['class'=>'left btn btn-info']) ?>
            <?php if($this->request->session()->read('Auth.User.role') >= \Cake\Core\Configure::read('role.admin')): ?>
                <?= $this->Form->button(__('Export VSA'), ['type'=>'button', 'class'=>'right btn btn-warning', 'id'=>'export_fitnet']) ?>
            <?php endif; ?>
        </div>
    </div>
    <?= $this->Form->end() ?>
</div>

<?php echo $this->Html->script('ManTime/man.export.js'); ?>
