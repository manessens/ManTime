
<div class="users index large-9 medium-8 columns content">
    <legend><?= __('Dashboard de ') ?><span class="text-primary"><?=  h($user->fullname); ?></span></legend>
    <div class="col-xs-10">
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Saisie de la semaine', 'content'=>'une image de calendrier']),
            ['controller' => 'Time', 'action' => 'index'],
            ['escape' => false]); ?>
    </div>
</div>
