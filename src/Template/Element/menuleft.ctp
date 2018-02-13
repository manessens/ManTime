
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
            <li class="heading"><?= __('Menu') ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => $controller,'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Articles'), ['controller' => 'Articles', 'action' => 'index']) ?></li>
            <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New'), ['controller' => $controller, 'action' => 'add']) ?></li>
    </ul>
</nav>
