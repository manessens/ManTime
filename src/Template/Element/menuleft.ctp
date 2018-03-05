<nav class="large-2 large-2bis medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Menu') ?></li>
        <li><?= $this->Html->link(__('Tableau de bord'), ['controller' => 'Board','action' => 'index']) ?></li>
        <?php if( $this->request->session()->read('Auth.User.admin') && !isset($controller) ): ?>
            <li class="heading"><?= __('Contrôle') ?></li>
            <li><?= $this->Html->link(__('Liste'), ['action' => 'index']) ?></li>
            <li><?= $this->Html->link(__('Ajouter'), ['action' => 'add']) ?></li>
        <?php endif; ?>
        <li class="heading"><?= __('Session') ?></li>
        <li><?= $this->Html->link(__('Profil'), ['controller' => 'Users', 'action' => 'profil']) ?></li>
        <li><?= $this->Html->link(__('Déconnexion'), ['controller' => 'Users', 'action' => 'logout']) ?></li>
    </ul>
</nav>
