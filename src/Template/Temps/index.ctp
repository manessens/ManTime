<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Temp[]|\Cake\Collection\CollectionInterface $temps
 */
?>
<script  type="text/javascript">

    var optionClients =  [];
    var optionProjects = [];
    var optionProfils  = [];
    var optionActivits = [];
    var valueClients =  [];
    var valueProjects = [];
    var valueProfils  = [];
    var valueActivits = [];

    $(function() {
    <?php foreach ($clients as $key => $value): ?>
        var arrayTemp = '<?php echo $key; ?>'.split('.');
        if (optionClients.hasOwnProperty(arrayTemp[0])) {
            optionClients[arrayTemp[0]].push('<?php echo $key; ?>');
        }else{
            optionClients[arrayTemp[0]]=[];
            optionClients[arrayTemp[0]].push('<?php echo $key; ?>');
        }
        valueClients['<?php echo $key; ?>'] = '<?php echo $value; ?>';
    <?php endforeach; ?>
    <?php foreach ($projects as $key => $value): ?>
        var arrayTemp = '<?php echo $key; ?>'.split('.');
        if (optionProjects.hasOwnProperty(arrayTemp[0])) {
            optionProjects[arrayTemp[0]].push('<?php echo $key; ?>');
        }else{
            optionProjects[arrayTemp[0]]=[];
            optionProjects[arrayTemp[0]].push('<?php echo $key; ?>');
        }
        valueProjects['<?php echo $key; ?>'] = '<?php echo $value; ?>';
    <?php endforeach; ?>
    <?php foreach ($profiles as $key => $value): ?>
        var arrayTemp = '<?php echo $key; ?>'.split('.');
        if (optionProfils.hasOwnProperty(arrayTemp[0])) {
            optionProfils[arrayTemp[0]].push('<?php echo $key; ?>');
        }else{
            optionProfils[arrayTemp[0]]=[];
            optionProfils[arrayTemp[0]].push('<?php echo $key; ?>');
        }
        valueProfils['<?php echo $key; ?>'] = '<?php echo $value; ?>';
    <?php endforeach; ?>
    <?php foreach ($activities as $key => $value): ?>
        var arrayTemp = '<?php echo $key; ?>'.split('.');
        if (optionActivits.hasOwnProperty(arrayTemp[0])) {
            optionActivits[arrayTemp[0]].push('<?php echo $key; ?>');
        }else{
            optionActivits[arrayTemp[0]]=[];
            optionActivits[arrayTemp[0]].push('<?php echo $key; ?>');
        }
        valueActivits['<?php echo $key; ?>'] = '<?php echo $value; ?>';
    <?php endforeach; ?>
    });

</script>

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
        <table id='semainier' cellpadding="0" cellspacing="0">
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
                <?php
                    $weekDays = ['Lu' => 0, 'Ma' => 0, 'Me' => 0, 'Je' => 0, 'Ve' => 0, 'Sa' => 0, 'Di' => 0];
                ?>
                <?php foreach ($week as $k => $line): ?>
                <tr id="<?php echo $k ?>">
                    <td scope="col" class="actions">
                        <?php if (!$validat): ?>
                            <button type="button" class="btn btn-danger remove">-</button>
                        <?php endif; ?>
                    </th>
                    <td scope="col" class="cel_client">
                        <?php if ($validat): ?>
                        <div>
                            <?php echo $clients[$line['idc']]; ?>
                        </div>
                        <?php else: ?>
                        <?php
                            echo $this->form->select('client['.$k.']', $clients, ['value' => $line['idc'], 'class' => 'client']);
                         ?>
                        <?php endif; ?>
                    </td>
                    <td scope="col" class="cel_projet">
                        <?php if ($validat): ?>
                        <div>
                            <?php echo $projects[$line['idp']]; ?>
                        </div>
                        <?php else: ?>
                        <?php
                            echo $this->form->select('projet['.$k.']', $projects, ['value' => $line['idp'], 'class' => 'project']);
                         ?>
                        <?php endif; ?>
                    </td>
                    <td scope="col" class="cel_profil">
                        <?php if ($validat): ?>
                        <div>
                            <?php echo $profiles[$line['id_profil']]; ?>
                        </div>
                        <?php else: ?>
                        <?php
                            echo $this->form->select('profil['.$k.']', $profiles, ['value' => $line['id_profil'], 'class' => 'profil']);
                         ?>
                        <?php endif; ?>
                    </td>
                    <td scope="col" class="cel_activit">
                        <?php if ($validat): ?>
                        <div>
                            <?php echo $activities[$line['ida']]; ?>
                        </div>
                        <?php else: ?>
                        <?php
                            echo $this->form->select('activities['.$k.']', $activities, ['value' => $line['ida'], 'class' => 'activit']);
                         ?>
                        <?php endif; ?>
                    </td>
                    <?php foreach ($weekDays as $idDay => $value): ?>
                        <td scope="col">
                            <?php if ($validat): ?>
                                <div style="text-align:center;">
                                <?php
                                    echo $line[$idDay]->time;
                                    if (!is_null($line[$idDay]->time)) {
                                        $weekDays[$idDay] += $line[$idDay]->time;
                                    }
                                ?>
                                </div>
                            <?php else: ?>
                            <?php
                                echo $this->Form->hidden("day.$k.$idDay.id", ['label' => false , 'value' => $line[$idDay]->idt]);
                                echo $this->form->control("day.$k.$idDay.time", ['label' => false , 'value' => $line[$idDay]->time]);
                             ?>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
                <tr id="total">
                    <td scope="col" class="actions">
                    <?php if (!$validat): ?>
                        <button id="add" type="button" class="btn btn-success">+</button>
                    <?php endif; ?>
                    </th>
                    <td scope="col"></td>
                    <td scope="col"></td>
                    <td scope="col"></td>
                    <td scope="col"></td>
                    <?php foreach ($weekDays as $idDay => $value): ?>
                        <td scope="col">
                            <div id="t<?php echo $idDay ?>" <?php if ($value>1){ echo 'style="color:red;"'; } ?> >
                            <?php if ($validat){ echo $value; } ?>
                            </div>
                        </td>
                    <?php endforeach; ?>
                </tr>
            </tbody>
        </table>
        <div class='right col-xs-5'>
            <div class = 'left'>
            <?php
                if ($validat) {
                    echo "<div class='header'>La semaine à déjà été validé. Modification impossible.</div>";
                }else{
                    echo $this->Form->control('validat', ['type' => 'checkbox' , 'label'=>'Valider la saisie (vérouille la saisie)']);
                }
            ?>
            </div>
        <?= $this->Form->button(__('Enregistrer'), ['class'=>'right btn btn-warning']) ?>
        </div>
    <?= $this->Form->end() ?>
</div>

<?php
    if (!$validat){
        echo $this->Html->script('ManTime/man.modal.js');
        echo $this->Html->script('ManTime/man.time.js');
    }
?>
