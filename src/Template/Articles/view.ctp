
<div class="articles view large-9 medium-8 columns content">
    <h1><?= h($article->title) ?></h1>
    <p><?= h($article->body) ?></p>
    <p><small>Créé le : <?= $article->created->format(DATE_RFC850) ?></small></p>
    <p><?= $this->Html->link('Modifier', ['action' => 'edit', $article->ref]) ?></p>
</div>
