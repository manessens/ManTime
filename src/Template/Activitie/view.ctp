<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Activitie $activitie
 */
?>
<div class="activitie view large-10 large-10bis medium-8 columns content">
    <legend><span class="text-danger"><?= h($activitie->nom_activit) ?></span></legend>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Activité') ?></th>
            <td><?= h($activitie->nom_activit) ?></td>
        </tr>
    </table>
    <div class="right">
        <div class="btn btn-warning"><?= $this->Html->link(__('Edition'), ['action' => 'edit', $activitie->ida]) ?></div>
    </div>
    <div class="related col-xs-6">
        <div class="col-xs-10 btn btn-danger"><?= $this->Form->postLink(__('Suppression'), ['action' => 'delete', $activitie->ida],
                ['confirm' => __("Êtes-vous sûr de vouloir supprimer l'activité {0}?", $activitie->nom_activit)]) ?></div>
    </div>
</div>
