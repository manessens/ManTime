<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Activitie $activitie
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $activitie->ida],
                ['confirm' => __('Are you sure you want to delete # {0}?', $activitie->ida)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Activitie'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="activitie form large-9 medium-8 columns content">
    <?= $this->Form->create($activitie) ?>
    <fieldset>
        <legend><?= __('Edit Activitie') ?></legend>
        <?php
            echo $this->Form->control('nom_activit');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer')) ?>
    <?= $this->Form->end() ?>
</div>
