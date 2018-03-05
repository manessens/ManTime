<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Temp[]|\Cake\Collection\CollectionInterface $temps
 */
?>
<div class="temps index large-10 large-10bis medium-8 columns content">
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Export') ?></legend>
        <?php echo $this->Form->select('Client', [1, 2, 3, 4, 5], ['empty' => '(choisissez)'] ); ?>
    </fieldset>
    <?= $this->Form->button(__('Télécharger'), ['class'=>'right btn btn-warning']) ?>
    <?= $this->Form->end() ?>
</div>
