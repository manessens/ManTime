<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Participant $participant
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Participant'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="participant form large-10 medium-8 columns content">
    <?= $this->Form->create($participant) ?>
    <fieldset>
        <legend><?= __('Ajouter Participant') ?></legend>
        <?php
        ?>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer'), ['class' => 'btn btn-warning']) ?>
    <?= $this->Form->end() ?>
</div>
