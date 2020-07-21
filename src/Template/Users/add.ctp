<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>

<div class="users form large-10 large-10bis medium-8 columns content">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __("Création d'un Consultant") ?></legend>
        <?php
            echo $this->Form->control('prenom');
            echo $this->Form->control('nom');
            echo $this->Form->control('email');
        ?>
        <div class="input text required">
        <?php
            echo $this->Form->label('ido','Origine');
            echo $this->Form->select('ido', $origineOption);
        ?>
        </div>
        <?php
            echo $this->Form->control('mdp', ['value' => 'Welcome1!']);
            echo $this->Form->control('modal', ['type' => 'checkbox', 'label' => ['text'=>'Saisie VSA en H ?']]);
            echo $this->Form->label('role','Rôle');
            echo $this->Form->select('role', $role, ['value' => $user->role]);
            // echo $this->Form->control('role',  ['type' => 'checkbox', 'label' => ['text'=>'Chef de projet', 'class' => 'text-primary']]);
            // echo $this->Form->control('admin', ['type' => 'checkbox', 'label' => ['class' => 'text-danger']]);
            echo $this->Form->control('actif', ['type' => 'checkbox']);

            echo $this->Form->control('id_fit', ['readonly','class'=> 'idf', "type" => 'text', 'label' => ['text'=>'Id VSA']]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer'), ['class' => 'btn btn-warning']) ?>
    <?= $this->Form->end() ?>

    <button type="button" id="linker" class="btn <?php echo $user->id_fit>0?"btn-success":"btn-primary"; ?>"
         data-toggle="modal" data-target="#linkModal" data-whatever="<?php echo $user->email ?>">Lier à VSA</button>
    <button type="button" id="resetter" class="btn btn-danger">Supprimer Id</button>
</div>


<?php echo $this->Html->script('ManTime/man.users.js'); ?>
<?php echo $this->Html->css('ManTime/man.loader.css'); ?>
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
