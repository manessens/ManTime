<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Activitie $activitie
 */
?>
<div class="activitie form large-10 large-10bis medium-8 columns content">
    <?= $this->Form->create($activitie) ?>
    <fieldset>
        <legend><?= __('Ajouter une activité') ?></legend>
        <?php
            echo $this->Form->control('nom_activit', ['label' => "Activité"]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer'), ['class' => 'btn btn-warning']) ?>
    <?= $this->Form->end() ?>
</div>
