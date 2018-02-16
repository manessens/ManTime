
<div class="users index large-9 medium-8 columns content">
    <h3 class="text-danger"><?= __('Dashboard administrateur de ') ?><?=  h($user->fullname); ?></h3>
    <div class="col-xs-12">
        <?= $this->Html->link($this->element('block', ['header' => 'Consultants', 'content'=>'bskgblk']), ['controller' => 'Users', 'action' => 'index']) ?>
    </div>
</div>
