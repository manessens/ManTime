<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>

<div class="users view large-9 medium-8 columns content">
    <h3><?= h($user->email) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Prenom') ?></th>
            <td><?= h($user->prenom) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Nom') ?></th>
            <td><?= h($user->nom) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Mdp') ?></th>
            <td><?= h($user->mdp) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Actif') ?></th>
            <td><?= $this->Number->format($user->actif) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Prem Connect') ?></th>
            <td><?= $this->Number->format($user->prem_connect) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Admin') ?></th>
            <td><?php if (?><?= $this->Number->format($user->admin) ?><?php ): ?>
                    Oui
                <?php else: ?>
                    Non
                <?php endif; ?>
            </td>
        </tr>
    </table>
    <!-- <div class="related">
        <h4><?= __('Related Articles') ?></h4>
        <?php if (!empty($user->articles)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Ida') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Title') ?></th>
                <th scope="col"><?= __('Body') ?></th>
                <th scope="col"><?= __('Published') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col"><?= __('Ref') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($user->articles as $articles): ?>
            <tr>
                <td><?= h($articles->ida) ?></td>
                <td><?= h($articles->user_id) ?></td>
                <td><?= h($articles->title) ?></td>
                <td><?= h($articles->body) ?></td>
                <td><?= h($articles->published) ?></td>
                <td><?= h($articles->created) ?></td>
                <td><?= h($articles->modified) ?></td>
                <td><?= h($articles->ref) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Articles', 'action' => 'view', $articles->ida]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Articles', 'action' => 'edit', $articles->ida]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Articles', 'action' => 'delete', $articles->ida], ['confirm' => __('Are you sure you want to delete # {0}?', $articles->ida)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div> -->
</div>
