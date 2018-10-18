
<div class="col-xs-12 new_content content">
    <legend>
        <?php if ($user->role >= \Cake\Core\Configure::read('role.admin')): ?>
            <span class="text-danger"><?= __('Tableau de bord administrateur de ') ?><?=  h($user->fullname); ?></span>
        <?php else: ?>
            <?= __('Tableau de bord de ') ?><span class="text-primary"><?=  h($user->fullname); ?></span>
        <?php endif; ?>
    </legend>
    <div class="col-xs-10">
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Saisie de la semaine', 'content'=>'Saisie des semaines', 'img'=>'Saisie_semaine.png', 'user'=>$user, 'auth'=>\Cake\Core\Configure::read('role.intern')]),
            ['controller' => 'Temps', 'action' => 'index'],
            ['escape' => false]); ?>
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Validation semaine', 'content'=>'Gestions des temps par semaines', 'img'=>'Validation_semaine.png', 'user'=>$user, 'auth'=>\Cake\Core\Configure::read('role.admin')]),
            ['controller' => 'Temps', 'action' => 'index-admin'],
            ['escape' => false]); ?>
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Export', 'content'=>'Export csv', 'img'=>'Export.png', 'user'=>$user, 'auth'=>\Cake\Core\Configure::read('role.cp')]),
            ['controller' => 'Temps', 'action' => 'export'],
            ['escape' => false]); ?>
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Consultants', 'content'=>'Gestions des consultants', 'img'=>'Consultants.png', 'user'=>$user, 'auth'=>\Cake\Core\Configure::read('role.admin')]),
            ['controller' => 'Users', 'action' => 'index'],
            ['escape' => false]); ?>
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Matrices', 'content'=>'Gestion des matrices', 'img'=>'Matrices.png', 'user'=>$user, 'auth'=>\Cake\Core\Configure::read('role.admin')]),
            ['controller' => 'Matrice', 'action' => 'index'],
            ['escape' => false]); ?>
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Projets', 'content'=>'Gestion des projets', 'img'=>'Projets.png', 'user'=>$user, 'auth'=>\Cake\Core\Configure::read('role.admin')]),
            ['controller' => 'Projet', 'action' => 'index'],
            ['escape' => false]); ?>
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Clients', 'content'=>'Gestion des clients', 'img'=>'Clients.png', 'user'=>$user, 'auth'=>\Cake\Core\Configure::read('role.admin')]),
            ['controller' => 'Client', 'action' => 'index'],
            ['escape' => false]); ?>
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Activités', 'content'=>'gestion des activités', 'img'=>'Activites.png', 'user'=>$user, 'auth'=>\Cake\Core\Configure::read('role.admin')]),
            ['controller' => 'Activitie', 'action' => 'index'],
            ['escape' => false]); ?>
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Fitnet', 'content'=>'Export fitnet', 'img'=>'Fitnet.png', 'user'=>$user, 'auth'=>\Cake\Core\Configure::read('role.admin')]),
            ['controller' => 'ExportFitnet', 'action' => 'index'],
            ['escape' => false]); ?>
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Outils', 'content'=>'Soumission personalisée', 'img'=>'Outils.png', 'user'=>$user, 'auth'=>\Cake\Core\Configure::read('role.admin')]),
            ['controller' => 'Utils', 'action' => 'index'],
            ['escape' => false]); ?>
    </div>
</div>
