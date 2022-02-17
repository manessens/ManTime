<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>


<div class="col-xs-12 new_content content">
    <legend>
        <?= $this->Html->link(__('<'), ['action' => 'index'], ['class' => 'btn btn-info']) ?> <?= __('Utilitaire - ') ?><span class="text-danger"><?= __('Export temps brut') ?></span>
    </legend>
    <div class="col-xs-10">
        <?= $this->Form->create($export, ['url'=> ['controller' => 'Utils', 'action' => 'exportrow']]) ?>
        <fieldset>
            <legend><?= __('Export') ?></legend>

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

            <?= $this->Form->button(__('Export local'), ['class'=>'left btn btn-info']) ?>

        </fieldset>
        <?= $this->Form->end() ?>
    </div>
</div>

<?php
    echo $this->Html->css('ManTime/man.loader.css');
    echo $this->Html->script('ManTime/man.export.js');
    echo $this->Html->script('ManTime/man.utils.js');
?>
