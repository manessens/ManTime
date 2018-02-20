<div class="btn-group">
    <?= $this->Html->link(__('Voir'), ['action' => 'view', $id], ['class' => 'btn btn-info']) ?>
    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $id], ['class' => 'btn btn-warning']) ?>
    <?= $this->Form->postLink(__('Suppr'), ['action' => 'delete', $id], ['class' => 'btn btn-danger',
        'confirm' => __("Êtes vous sûr de supprimer l'entité : {0}?", $entity)]) ?>
</div>
