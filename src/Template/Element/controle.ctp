<div class="btn-group">
    <div><?= $this->Html->link(__('Voir'), ['action' => 'view', $id], ['class' => 'btn btn-info']) ?></div>
    <div class="btn btn-warning"><?= $this->Html->link(__('Edit'), ['action' => 'edit', $id], ['class' => 'btn btn-info']) ?></div>
    <div class="btn btn-danger"><?= $this->Form->postLink(__('Suppr'), ['action' => 'delete', $id], ['class' => 'btn btn-info'],
            ['confirm' => __("Êtes vous sûr de supprimer l'entité : {0}?", $entity)]) ?></div>
</div>
