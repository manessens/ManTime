<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Matrice $matrice
 */
?>
<div class="matrice view large-9 medium-8 columns content">
    <h3><?= h($matrice->idm) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Nom Matrice') ?></th>
            <td><?= h($matrice->nom_matrice) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Idm') ?></th>
            <td><?= $this->Number->format($matrice->idm) ?></td>
        </tr>
    </table>
</div>
