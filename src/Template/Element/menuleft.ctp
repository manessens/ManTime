<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
            <li class="heading"><?= __('Navigation') ?></li>
        <li><?= $this->Html->link(__('Liste Consultants'), ['controller' => 'Users','action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Liste Articles'), ['controller' => 'Articles', 'action' => 'index']) ?></li>
        <?php if( isset($controller) ): ?>
        <li class="heading"><?= __('Actions') ?></li>
        <li><div class=" btn btn-primary btn-xs"><?= $this->Html->link(__('Créer'), ['controller' => $controller, 'action' => 'add']) ?></div></li>
        <?php endif; ?>
        <li class="heading"><?= __('Session') ?></li>
        <li><?= $this->Html->link(__('Déconnexion'), ['controller' => 'Users', 'action' => 'logout']) ?></li>
    </ul>
</nav>
