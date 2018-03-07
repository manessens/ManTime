
<div class="users index large-10 large-10bis medium-8 columns content">
    <legend><?= __('Tableau de bord de ') ?><span class="text-primary"><?=  h($user->fullname); ?></span></legend>
    <div class="col-xs-10">
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Saisie de la semaine', 'content'=>'Saisie des semaines', 'img'=>'calendario.png']),
            ['controller' => 'Temps', 'action' => 'index'],
            ['escape' => false]); ?>
    </div>
</div>
