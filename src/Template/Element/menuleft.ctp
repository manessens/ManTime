<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Menu') ?></li>
        <li><?= $this->Html->link(__('Dashboard'), ['controller' => 'Board','action' => 'index']) ?></li>
        <?php if( !isset($controller) ): ?>
            <li class="heading"><?= __('Contrôle') ?></li>
            <li><?= $this->Html->link(__('Liste'), ['action' => 'index']) ?></li>
            <?= $this->set('test',h($user->fullname));  ?>
            <?php pr($test);exit; ?>

            <?php if ($user['admin']): ?>
                <li><?= $this->Html->link(__('Ajouter'), ['action' => 'add']) ?></li>
            <?php endif; ?>
        <?php endif; ?>
        <li class="heading"><?= __('Session') ?></li>
        <li><?= $this->Html->link(__('Déconnexion'), ['controller' => 'Users', 'action' => 'logout']) ?></li>
    </ul>
</nav>
