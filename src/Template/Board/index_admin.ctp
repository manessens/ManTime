
<div class="users index large-10 large-10bis medium-8 columns content">
    <h3 class="text-danger"><?= __('Tableau de bord administrateur de ') ?><?=  h($user->fullname); ?></h3>
    <div class="col-xs-10">
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Validation semaine', 'content'=>'Gestions des temps par semaines', 'img'=>'calendario.png']),
            ['controller' => 'Temps', 'action' => 'index-admin'],
            ['escape' => false]); ?>
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Export', 'content'=>'Export csv', 'img'=>'24543.png']),
            ['controller' => 'Temps', 'action' => 'export'],
            ['escape' => false]); ?>
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Consultants', 'content'=>'Gestions des consultants', 'img'=>'Avatar_homme.png']),
            ['controller' => 'Users', 'action' => 'index'],
            ['escape' => false]); ?>
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Matrices', 'content'=>'Gestion des matrices', 'img'=>'matrice-toeplitz.gif']),
            ['controller' => 'Matrice', 'action' => 'index'],
            ['escape' => false]); ?>
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Clients', 'content'=>'une autre image un autre jour']),
            ['controller' => 'Client', 'action' => 'index'],
            ['escape' => false]); ?>
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Projets', 'content'=>'une autre image un jour certain']),
            ['controller' => 'Projet', 'action' => 'index'],
            ['escape' => false]); ?>
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Activités', 'content'=>'une autre image un jour certain']),
            ['controller' => 'Activitie', 'action' => 'index'],
            ['escape' => false]); ?>
    </div>
</div>
