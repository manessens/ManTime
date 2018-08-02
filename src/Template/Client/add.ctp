<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Client $client
 */
?>
<div class="client form large-10 large-10bis medium-8 columns content">
    <?= $this->Form->create($client) ?>
    <fieldset>
        <legend><?= __('Ajouter un client') ?></legend>
        <?php
            echo $this->Form->control('nom_client');
        ?>
        <div class="input text required">
        <?php
            echo $this->Form->label('id_agence','Agence');
            echo $this->Form->select('id_agence', $agenceOption, ['id'=> 'id_agence']);

            echo $this->Form->control('id_fit', ['readonly','class'=> 'idf', "type" => 'text', 'label' => ['text'=>'Id Fitnet']]);

            echo $this->Form->label('liste_fit','Liste Fitnet');
            echo $this->Form->select('liste_fit', array(), ['id'=> 'liste_fitnet']);
        ?>
        </div>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer'), ['class' => 'btn btn-warning']) ?>
    <?= $this->Form->end() ?>
    <button type="button" id="linker" class="btn <?php echo $client->id_fit>0?"btn-success":"btn-primary"; ?>"
         data-toggle="modal" data-target="#linkModal" data-whatever="">Actualiser liste Fitnet</button>
</div>

<?php echo $this->Html->script('ManTime/man.client.js'); ?>
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
              <label for="recipient-name" class="col-form-label">Agence:</label>
              <?php echo $this->Form->select('id_agence', $agenceOption); ?>
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
