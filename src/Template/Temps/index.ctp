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
                    <?php echo("Semaine du ".$lundi->i18nFormat('dd/MM').' au '.$dimanche->i18nFormat('dd/MM')); ?>
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
                    <th class="weekend" scope="col"><?= h('Sa') ?></th>
                    <th class="weekend" scope="col"><?= h('Di') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($week as $k => $line): ?>
                <tr id="<?php echo $k ?>">
                    <td scope="col" class="actions"><button type="button" class="btn btn-danger">-</button></th>
                    <td scope="col">
                        <?php
                            echo $this->form->select('client['.$k.']', $clients);
                         ?>
                    </td>
                    <td scope="col">
                        <?php
                            echo $this->form->select('projet['.$k.']', $projects, ['value' => $line['idp']]);
                         ?>
                    </td>
                    <td scope="col">
                        <?php
                            echo $this->form->select('profil['.$k.']', $profiles, ['value' => $line['id_profil']]);
                         ?>
                    </td>
                    <td scope="col">
                        <?php
                            echo $this->form->select('activities['.$k.']', $activities, ['value' => $line['ida']]);
                         ?>
                    </td>
                    <td scope="col">
                        <?php
                            echo $this->form->control('day['.$k.']["Lu"].time');
                         ?>
                    </td>
                    <td scope="col">
                        <?php
                            // echo $this->form->control('day['.$k.']["Ma"]', $line['Lu']->time);
                         ?>
                    </td>
                    <td scope="col">
                        <?php
                            // echo $this->form->control('day['.$k.']["Me"]', $line['Lu']->time);
                         ?>
                    </td>
                    <td scope="col">
                        <?php
                            // echo $this->form->control('day['.$k.']["Je"]', $line['Lu']->time);
                         ?>
                    </td>
                    <td scope="col">
                        <?php
                            // echo $this->form->control('day['.$k.']["Ve"]', $line['Lu']->time);
                         ?>
                    </td>
                    <td scope="col">
                        <?php
                            // echo $this->form->control('day['.$k.']["Sa"]', $line['Lu']->time);
                         ?>
                    </td>
                    <td scope="col">
                        <?php
                            // echo $this->form->control('day['.$k.']["Di"]', $line['Lu']->time);
                         ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr id="">
                    <td scope="col" class="actions"><button type="button" class="btn btn-success">+</button></th>
                    <td scope="col"></td>
                    <td scope="col"></td>
                    <td scope="col"></td>
                    <td scope="col"></td>
                    <td scope="col"></td>
                    <td scope="col"></td>
                    <td scope="col"></td>
                    <td scope="col"></td>
                    <td scope="col"></td>
                    <td scope="col"></td>
                    <td scope="col"></td>
                </tr>
            </tbody>
        </table>
        <?= $this->Form->button(__('Enregistrer'), ['class'=>'right btn btn-warning']) ?>
    <?= $this->Form->end() ?>
</div>
