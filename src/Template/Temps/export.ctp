<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Temp[]|\Cake\Collection\CollectionInterface $temps
 */
?>
<div class="temps index large-10 large-10bis medium-8 columns content">
    <legend><?= __('Export') ?></legend>
    <?= $this->Form->create() ?>
        <div class='right col-xs-5'>
        <?= $this->Form->button(__('Télécharger'), ['class'=>'right btn btn-warning']) ?>
        </div>
    <?= $this->Form->end() ?>
</div>
