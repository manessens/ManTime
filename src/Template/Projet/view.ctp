<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Projet $projet
 */
?>
<?php
    function cmpu($a, $b) {
        if ($a->user->fullname == $b->user->fullname) {
            return 0;
        }
        return ($a->user->fullname < $b->user->fullname) ? -1 : 1;
    }
    function cmpa($a, $b) {
        if ($a->activitie->nom_activit == $b->activitie->nom_activit) {
            return 0;
        }
        return ($a->activitie->nom_activit < $b->activitie->nom_activit) ? -1 : 1;
    }
?>

<div class="projet view large-10 large-10bis medium-8 columns content">
    <h3><?= h($projet->nom_projet) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Nom_projet') ?></th>
            <td><?= h($projet->nom_projet) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Client') ?></th>
            <td><?= h($projet->client->nom_client) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Date de debut') ?></th>
            <td><?=  h($projet->date_debut->i18nFormat('dd-MM-yyyy')) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Date de fin') ?></th>
            <td><?=  h($projet->date_fin->i18nFormat('dd-MM-yyyy')) ?></td>
        </tr>
    </table>
    <div class="col-xs-6">
        <legend class="header"><?= __('Participant') ?></legend>
        <ul class="list-group">
        <?php
            $participants = $projet->participant;
            uasort($participants, 'cmpu');
            $activities = $projet->activities;
            uasort($activities, 'cmpa');
        ?>
        <?php foreach ($participants as $participant): ?>
            <li class="list-group-item"><?= $participant->user->fullname ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
    <div class="col-xs-6">
        <legend class="header"><?= __('Activités') ?></legend>
        <ul class="list-group">
        <?php foreach ($activities as $activity): ?>
            <li class="list-group-item"><?= $activity->activitie->nom_activit ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
    <div class="right">
        <div class="btn btn-warning"><?= $this->Html->link(__('Edition'), ['action' => 'edit', $projet->idp]) ?></div>
    </div>
    <div class="related col-xs-6">
        <div class="col-xs-10 btn btn-danger"><?= $this->Form->postLink(__('Suppression'), ['action' => 'delete', $projet->idp],
                ['confirm' => __('Êtes-vous sûr de vouloir supprimer le projet {0}?', $projet->nom_projet)]) ?></div>
    </div>
</div>
