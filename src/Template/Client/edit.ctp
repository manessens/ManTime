<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Client $client
 */
?>
<div class="client form large-10 large-10bis medium-8 columns content">
    <?= $this->Form->create($client) ?>
    <fieldset>
        <legend>
            <?= __('Edition du client') ?> <span class="text-danger"><?= h($client->nom_client) ?></span>
        </legend>
        <?php
            echo $this->Form->control('nom_client');
        ?>
        <div class="input text required">
        <?php
            echo $this->Form->label('id_agence','Agence');
            echo $this->Form->select('id_agence', $agenceOption, ['id'=> 'id_agence']);
        ?>
        </div>
        <div class="left idf">
        <?php
            echo $this->Form->control('id_fit', ['readonly', "type" => 'text', 'label' => ['text'=>'Id VSA']]);
        ?>
        </div>
        <div class="left col-xs-8">
                <?php
                    echo $this->Form->label('select_vsa','Liste VSA');
                ?>
                <select name="select_vsa" type="text" id="liste_vsa"></select>
         </div>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer'), ['class' => 'btn btn-warning']) ?>
    <?= $this->Form->end() ?>
    <button type="button" id="linker" class="btn <?php echo $client->id_fit>0?"btn-success":"btn-primary"; ?>"
         data-toggle="modal" data-target="#linkModal" data-whatever="">Actualiser liste VSA</button>
    <button type="button" id="resetter" class="btn btn-danger">Supprimer Id</button>
    <div class="loader btn" style="display:none;" id="loader"> </div>
</div>

<!-- addtional style and script for this page only -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<?php echo $this->Html->script('ManTime/man.client.js'); ?>
<?php echo $this->Html->css('ManTime/man.loader.css'); ?>
