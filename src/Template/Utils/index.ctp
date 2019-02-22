<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>

<div class="col-xs-12 new_content content">
    <legend>
        <?= __('Utilitaire - ') ?><span class="text-danger"><?= __('soumission personalisée') ?></span> semaine #<span id='nsemaine'><?php echo $semaine ?></span> - <span id="nannee"><?php echo $annee ?></span>
    </legend>
    <div class="col-xs-10">
        <div class="list-group">
          <a href="/utils/sendtime" class="list-group-item">First item</a>
          <a href="#" class="list-group-item">Second item</a>
          <a href="#" class="list-group-item">Third item</a>
        </div>
    </div>
</div>

<?php
    echo $this->Html->css('ManTime/man.loader.css');
    echo $this->Html->script('ManTime/man.utils.js');
?>
