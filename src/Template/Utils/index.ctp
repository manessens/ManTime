<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>

<div class="col-xs-12 new_content content">
    <legend><?= __('Utilitaires') ?></legend>
    <div class="col-xs-10">
        <div class="list-group">
          <a href="/utils/sendtime" class="list-group-item"><?= __('Soumission des temps personalisée') ?></a>
          <a href="/utils/authfit" class="list-group-item"><?= __('Authentificateur pour fitnet') ?></a>
          <!-- <a href="#" class="list-group-item">Third item</a> -->
          <a href="/utils/authvsa" class="list-group-item"><?= __('Authentificateur pour VSA') ?></a>
        </div>
    </div>
</div>

<?php
    echo $this->Html->css('ManTime/man.loader.css');
    echo $this->Html->script('ManTime/man.utils.js');
?>
