<!-- <?php $this->set('controller', 'Articles'); ?>
<?= $this->element('menuleft') ?> -->
<div class="articles view large-10 large-10bis medium-8 columns content">
    <h1>Modifier un article</h1>
    <?php
        echo $this->Form->create($article);
        echo $this->Form->control('user_id', ['type' => 'hidden']);
        echo $this->Form->control('title');
        echo $this->Form->control('body', ['rows' => '3']);
        echo $this->Form->button(__('Sauvegarder l\'article'));
        echo $this->Form->end();
    ?>
</div>
