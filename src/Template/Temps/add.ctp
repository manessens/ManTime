<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Temp $temp
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Temps'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="temps form large-10 large-10bis medium-8 columns content">
    <?= $this->Form->create($temp) ?>
    <fieldset>
        <legend><?= __('Ajouter Temp') ?></legend>
        <?php
            echo $this->Form->control('date');
            echo $this->Form->control('time');
            echo $this->Form->control('idu');
            echo $this->Form->control('idp');
            echo $this->Form->control('id_profil');
            echo $this->Form->control('ida');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer'), ['class' => 'btn btn-warning']) ?>
    <?= $this->Form->end() ?>
</div>
