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

    <?php if ($restrict == ''):?>
        <?= $this->Form->postLink(__('Suppr'), ['action' => 'delete', $id], ['class' => 'btn btn-danger',
            'confirm' => __("Êtes vous sûr de supprimer l'entité : {0}?", $entity)]) ?>
    <?php elseif($restrict == '%delete%'): ?>
        <?= $this->Form->postLink(__('Suppr'), ['action' => 'delete', $id], ['class' => 'btn btn-danger',
            'confirm' => __("Êtes vous sûr de supprimer la ligne ?")]) ?>
    <?php elseif($restrict == '%mod%'): ?>
        <?= $this->Form->postLink(__('Suppr'), ['action' => 'delete', $id], ['class' => 'btn btn-danger',
            'confirm' => __("Êtes vous sûr de vouloir supprimer le marquage d'erreur ?")]) ?>
    <?php endif; ?>
</div>
