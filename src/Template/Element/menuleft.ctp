<nav class="large-2 large-2bis medium-4 columns" id="actions-sidebar">
    <!-- <div class="menu_fixed"> -->
        <ul class="side-nav">
            <?php if( $this->request->session()->read('Auth.User.role') >= \Cake\Core\Configure::read('role.admin') && !isset($controller) ): ?>
                <li class="heading"><?= __('Contrôle') ?></li>
                <li><?= $this->Html->link(__('Liste'), ['action' => 'index']) ?></li>
                <li><?= $this->Html->link(__('Ajouter'), ['action' => 'add']) ?></li>
            <?php endif; ?>
        </ul>
    <!-- </div> -->
</nav>
