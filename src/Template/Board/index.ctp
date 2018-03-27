
<div class="users index large-10 large-10bis medium-8 columns content">
    <legend><?= __('Tableau de bord de ') ?><span class="text-primary"><?=  h($user->fullname); ?></span></legend>
    <div class="col-xs-10">
        <?= $this->Html->link($this->element(
            'block',
            ['title' => 'Saisie de la semaine', 'content'=>'Saisie des semaines', 'img'=>'Saisie_semaine.png']),
            ['controller' => 'Temps', 'action' => 'index'],
            ['escape' => false]); ?>
        <?php if ($this->request->session()->read('Auth.User.role') >= 20): ?>
            <?= $this->Html->link($this->element(
                'block',
                ['title' => 'Export', 'content'=>'Export csv', 'img'=>'Export.png']),
                ['controller' => 'Temps', 'action' => 'export'],
                ['escape' => false]); ?>
        <?php endif; ?>
    </div>
</div>
