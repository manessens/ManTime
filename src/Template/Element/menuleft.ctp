
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
            <li class="heading"><?= __('Menu') ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users','action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Articles'), ['controller' => 'Articles', 'action' => 'index']) ?></li>
            <li class="heading"><?= __('Actions') ?></li>
        <?php if isset($controller): ?>
        <li><?= $this->Html->link(__('New'), ['controller' => $controller, 'action' => 'add']) ?></li>
        <?php endif; ?>
    </ul>
</nav>
