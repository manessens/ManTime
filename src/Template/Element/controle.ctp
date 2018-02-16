<div class="btn-group">
    <div class="btn btn-info"><?= $this->Html->link(__('Voir'), ['action' => 'view', $id]) ?></div>
    <div class="btn btn-warning"><?= $this->Html->link(__('Edit'), ['action' => 'edit', $id]) ?></div>
    <div class="btn btn-danger"><?= $this->Form->postLink(__('Suppr'), ['action' => 'delete', $id],
            ['confirm' => __("Êtes vous sûr de supprimer l'entité : {0}?", $entity)]) ?></div>
</div>
