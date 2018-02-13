
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
            <li class="heading"><?= __('Menu') ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users','action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Articles'), ['controller' => 'Articles', 'action' => 'index']) ?></li>
            <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Article'), ['controller' => 'Articles', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="articles view large-9 medium-8 columns content">
    <h1>Articles</h1>
    <?= $this->Html->link('Ajouter un article', ['action' => 'add']) ?>
    <table>
        <tr>
            <th>Titre</th>
            <th>Créé le</th>
        </tr>

        <!-- C'est ici que nous bouclons sur notre objet Query $articles pour afficher les informations de chaque article -->

        <?php foreach ($articles as $article): ?>
        <tr>
            <td>
                <?= $this->Html->link($article->title, ['action' => 'view', $article->ref]) ?>
            </td>
            <td>
                <?= $article->created->format(DATE_RFC850) ?>
            </td>
            <td>
                <?= $this->Html->link('Modifier', ['action' => 'edit', $article->ref]) ?>
                <?= $this->Form->postLink(
                    'Supprimer',
                    ['action' => 'delete', $article->ref],
                    ['confirm' => 'Êtes-vous sûr ?'])
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
