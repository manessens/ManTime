<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Client $client
 */
?>
<div class="client view large-9 medium-8 columns content">
    <h3><?= h($client->idc) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Nom Client') ?></th>
            <td><?= h($client->nom_client) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Idc') ?></th>
            <td><?= $this->Number->format($client->idc) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Prix') ?></th>
            <td><?= $this->Number->format($client->prix) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Matrice') ?></th>
            <td><?= h($client->matrice->nom_matrice) ?></td>
        </tr>
    </table>
</div>
