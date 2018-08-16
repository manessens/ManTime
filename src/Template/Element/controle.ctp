<div class="btn-group">
    <?php
        if (!isset($restrict)) {
            $restrict = "";
        }
     ?>
    <?php if ($restrict != '%delete%'):?>
        <?= $this->Html->link(__('Voir'), ['action' => 'view', $id], ['class' => 'btn btn-info']) ?>

        <?php if ($restrict != '%show%'):?>
            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $id], ['class' => 'btn btn-warning']) ?>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($restrict != '%show%'):?>
        <?= $this->Form->postLink(__('Suppr'), ['action' => 'delete', $id], ['class' => 'btn btn-danger',
            'confirm' => __("Êtes vous sûr de supprimer l'entité : {0}?", $entity)]) ?>
    <?php elseif($restrict == '%show%'): ?>
        <?= $this->Form->postLink(__('Suppr'), ['action' => 'delete', $id], ['class' => 'btn btn-danger',
            'confirm' => __("Êtes vous sûr de supprimer la ligne ?")]) ?>
    <?php endif; ?>
</div>
