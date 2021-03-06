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
    <legend>
        <span class="text-danger"><?= h($projet->nom_projet) ?></span>
        <?= $this->element('link2fitnet', ['idf'=>$projet->id_fit]) ?>
    </legend>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Nom_projet') ?></th>
            <td><?= h($projet->projname) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Client') ?></th>
            <td><?= h($projet->client->nom_client) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Facturable') ?></th>
            <td><?= h($projet->facturable->nom_fact) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Matrice') ?></th>
            <td><?= h($projet->matrice->nom_matrice) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Prix UO') ?></th>
            <td><?= $this->Number->format($projet->prix) ?>€</td>
        </tr>
        <tr>
            <th scope="row"><?= __('CP référent') ?></th>
            <td><?= h($projet->User->fullname) ?></td>
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
