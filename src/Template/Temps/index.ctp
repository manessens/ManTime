<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Temp[]|\Cake\Collection\CollectionInterface $temps
 */
?>
<div class="temps index large-10 medium-8 columns content">
    <?php if ($current == $semaine): ?>
        <h3><?= __('Saisie de la semaine courrante #') ?><?= $semaine ?></h3>
    <?php else: ?>
        <legend><?= __('Saisie de la semaine #') ?><?= $semaine ?></legend>
    <?php endif; ?>
    <?= $this->Form->create() ?>
        <div class="block col-xs-12">
            <div class="left badge back-success"><?= h($fullNameUserAuth) ?></div>
            <div class="controler right">
                <div>
                    <?php if ($semaine-1 < 1 ): ?>
                        <?= $this->Html->link(__('<'), ['action' => 'index', 52, $annee-1], ['class' => 'btn btn-success']) ?>
                    <?php else: ?>
                        <?= $this->Html->link(__('<'), ['action' => 'index', $semaine-1, $annee], ['class' => 'btn btn-success']) ?>
                    <?php endif; ?>
                    <?= h("Semaine du au") ?>
                    <?php if ($semaine+1 > 52 ): ?>
                        <?= $this->Html->link(__('>'), ['action' => 'index', 1, $annee+1], ['class' => 'btn btn-success']) ?>
                    <?php else: ?>
                        <?= $this->Html->link(__('>'), ['action' => 'index', $semaine+1, $annee], ['class' => 'btn btn-success']) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="blank col-xs-12"></div>
        <table cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col" class="supp"></th>
                    <th scope="col"><?= h('Client') ?></th>
                    <th scope="col"><?= h('Projet') ?></th>
                    <th scope="col"><?= h('Profil') ?></th>
                    <th scope="col"><?= h('Activité') ?></th>
                    <th class="semaine" scope="col"><?= h('Lu') ?></th>
                    <th class="semaine" scope="col"><?= h('Ma') ?></th>
                    <th class="semaine" scope="col"><?= h('Me') ?></th>
                    <th class="semaine" scope="col"><?= h('Je') ?></th>
                    <th class="semaine" scope="col"><?= h('Ve') ?></th>
                    <th class="semaine" scope="col"><?= h('Sa') ?></th>
                    <th class="semaine" scope="col"><?= h('Di') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($week as $line): ?>
                <tr id="">
                    <td class="actions"> </td>
                    <td scope="col" class="actions"><?= __('+/-') ?></th>
                    <td scope="col"><?= h('Client') ?></td>
                    <td scope="col"><?= h('Projet') ?></td>
                    <td scope="col"><?= h('Profil') ?></td>
                    <td scope="col"><?= h('Activité') ?></td>
                    <td scope="col"><?= h('Lu') ?></td>
                    <td scope="col"><?= h('Ma') ?></td>
                    <td scope="col"><?= h('Me') ?></td>
                    <td scope="col"><?= h('Je') ?></td>
                    <td scope="col"><?= h('Ve') ?></td>
                    <td scope="col"><?= h('Sa') ?></td>
                    <td scope="col"><?= h('Di') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= $this->Form->button(__('Enregistrer'), ['class'=>'btn btn-warning']) ?>
    <?= $this->Form->end() ?>
</div>
