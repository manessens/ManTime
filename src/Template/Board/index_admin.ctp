
<div class="users index large-9 medium-8 columns content">
    <h3 class="text-danger"><?= __('Dashboard administrateur de ') ?><?=  h($user->fullname); ?></h3>
    <div class="col-xs-10">
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Consultants', 'content'=>'une image un jour']),
            ['controller' => 'Users', 'action' => 'index'],
            ['escape' => false]); ?>
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Matrices', 'content'=>'une autre image un autre jour']),
            ['controller' => 'Matrice', 'action' => 'index'],
            ['escape' => false]); ?>
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Clients', 'content'=>'une autre image un autre jour']),
            ['controller' => 'Client', 'action' => 'index'],
            ['escape' => false]); ?>
    </div>
</div>
