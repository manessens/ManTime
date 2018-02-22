<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Projet $projet
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $projet->idp],
                ['confirm' => __('Are you sure you want to delete # {0}?', $projet->idp)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Projet'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="projet form large-9 medium-8 columns content">
    <?= $this->Form->create($projet) ?>
    <fieldset>
        <legend><?= __('Edit Projet') ?></legend>
        <?php
            echo $this->Form->control('idc');
            echo $this->Form->control('date_debut', ['type' => 'text', 'label' => 'Date de début', 'class'=>'datepicker']);
            // echo $this->Form->control('date_fin', ['type' => 'text', 'label' => 'Date de début', 'class'=>'datepicker']);
        ?>
        <div class="input text required">
            <label for="date_fin">Date de fin</label>
            <input type="date" name="date_fin"  value="<?= $projet->date_fin->i18nFormat('yyyy-MM-dd') ?>">
        </div>
    </fieldset>
    <?= $this->Form->button(__('Enregistrer')) ?>
    <?= $this->Form->end() ?>
</div>

<?php echo $this->Html->script('ManTime/man.projet.js'); ?>
