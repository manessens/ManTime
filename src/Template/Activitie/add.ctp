<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Activitie $activitie
 */
?>
<div class="activitie form large-9 medium-8 columns content">
    <?= $this->Form->create($activitie) ?>
    <fieldset>
        <legend><?= __('Ajouter une activité') ?></legend>
        <?php
            echo $this->Form->control('nom_activit', ['label' => "activité"]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer')) ?>
    <?= $this->Form->end() ?>
</div>
