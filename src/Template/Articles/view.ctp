
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Menu') ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users','action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Articles'), ['controller' => 'Articles', 'action' => 'index']) ?></li>
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Article'), ['action' => 'edit', $article->idu]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Article'), ['action' => 'delete', $article->idu], ['confirm' => __('Are you sure you want to delete # {0}?', $article->ref)]) ?> </li>
    </ul>
</nav>

<div class="articles view large-9 medium-8 columns content">
    <h1><?= h($article->title) ?></h1>
    <p><?= h($article->body) ?></p>
    <p><small>Créé le : <?= $article->created->format(DATE_RFC850) ?></small></p>
    <p><?= $this->Html->link('Modifier', ['action' => 'edit', $article->ref]) ?></p>
</div>
