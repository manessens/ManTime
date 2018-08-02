<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>

<div class="users form large-10 large-10bis medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Modification de : ');?> <span class="text-danger"><?=  h($user->fullname); ?></span></legend>
        <?php
            echo $this->Form->control('email');
            echo $this->Form->control('prenom');
            echo $this->Form->control('nom');
        ?>
        <div class="input text required">
        <?php
            echo $this->Form->label('ido','Origine');
            echo $this->Form->select('ido', $origineOption);
        ?>
        </div>
        <?php
            // echo $this->Form->control('mdp');
            echo $this->Form->control('actif', ['type' => 'checkbox', 'label'=>['class'=>'checkboxU'] ]);
            echo $this->Form->control('prem_connect', ['type' => 'checkbox', 'class'=>'reset', 'label'=>['text'=>'Réinitialisation mot de passe', 'class'=>'checkboxU'] ]);

            echo $this->Form->control('role',  ['value'=> $user->isProject,'type' => 'checkbox', 'label' => ['text'=>'Chef de projet', 'class' => 'text-primary checkboxU']]);
            echo $this->Form->control('admin', ['value'=> $user->isAdmin,'type' => 'checkbox', 'label' => ['class' => 'text-danger checkboxU']]);

            echo $this->Form->control('id_fit', ['readonly','class'=> 'idf', "type" => 'text', 'label' => ['text'=>'Id Fitnet']]);

         ?>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer'), ['class' => 'btn btn-warning']) ?>
    <?= $this->Form->end() ?>

    <button type="button" id="linker" class="btn <?php echo $user->id_fit>0?"btn-success":"btn-primary"; ?>"
         data-toggle="modal" data-target="#linkModal" data-whatever="<?php echo $user->email ?>">Lier à Fitnet</button>

</div>


<?php echo $this->Html->script('ManTime/man.users.js'); ?>
<?php echo $this->Html->css('ManTime/man.loader.css'); ?>
<!-- modal reset password -->
<div class="modal" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="myModalLabel">Réinitialiser le mot de passe</h3>
            </div>
            <div class="modal-body">
                Le mot de passe sera définie à "Welcome1!".
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<!-- modal link with fitnet -->
<div class="modal fade" id="linkModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="ajax">
        <div class="modal-body">
            <div class="form-group">
              <label for="recipient-name" class="col-form-label">Email:</label>
              <input type="text" class="form-control" id="recipient-name">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" data-dismiss="modal">Annuler</button>
          <button type="submit" id="send" class="btn btn-primary">Rechercher</button>
          <div class="loader btn" style="display:none;" id="loader"> </div>
        </div>
      </form>
    </div>
  </div>
</div>
