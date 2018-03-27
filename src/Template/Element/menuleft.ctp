<nav class="large-2 large-2bis medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <?php if( $this->request->session()->read('Auth.User.role') >= 50 && !isset($controller) ): ?>
            <li class="heading"><?= __('Contrôle') ?></li>
            <li><?= $this->Html->link(__('Liste'), ['action' => 'index']) ?></li>
            <li><?= $this->Html->link(__('Ajouter'), ['action' => 'add']) ?></li>
        <?php endif; ?>
    </ul>
</nav>
