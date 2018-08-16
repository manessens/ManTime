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
        var arrayTemp = '<?php echo addslashes($key); ?>'.split('.');
        if (optionClients.hasOwnProperty(arrayTemp[1])) {
            optionClients[arrayTemp[1]].push('<?php echo addslashes($key); ?>');
        }else{
            optionClients[arrayTemp[1]]=[];
            optionClients[arrayTemp[1]].push('<?php echo addslashes($key); ?>');
        }
        valueClients['<?php echo addslashes($key); ?>'] = '<?php echo addslashes($value); ?>';
    <?php endforeach; ?>
    <?php foreach ($projects as $key => $value): ?>
        var arrayTemp = '<?php echo addslashes($key); ?>'.split('.');
        if (optionProjects.hasOwnProperty(arrayTemp[1])) {
            optionProjects[arrayTemp[1]].push('<?php echo addslashes($key); ?>');
        }else{
            optionProjects[arrayTemp[1]]=[];
            optionProjects[arrayTemp[1]].push('<?php echo addslashes($key); ?>');
        }
        valueProjects['<?php echo addslashes($key); ?>'] = '<?php echo addslashes($value); ?>';
    <?php endforeach; ?>
    <?php foreach ($profiles as $key => $value): ?>
        var arrayTemp = '<?php echo addslashes($key); ?>'.split('.');
        if (optionProfils.hasOwnProperty(arrayTemp[0])) {
            optionProfils[arrayTemp[0]].push('<?php echo addslashes($key); ?>');
        }else{
            optionProfils[arrayTemp[0]]=[];
            optionProfils[arrayTemp[0]].push('<?php echo addslashes($key); ?>');
        }
        valueProfils['<?php echo addslashes($key); ?>'] = '<?php echo addslashes($value); ?>';
    <?php endforeach; ?>
    <?php foreach ($activities as $key => $value): ?>
        var arrayTemp = '<?php echo addslashes($key); ?>'.split('.');
        if (optionActivits.hasOwnProperty(arrayTemp[0])) {
            optionActivits[arrayTemp[0]].push('<?php echo addslashes($key); ?>');
        }else{
            optionActivits[arrayTemp[0]]=[];
            optionActivits[arrayTemp[0]].push('<?php echo addslashes($key); ?>');
        }
        valueActivits['<?php echo addslashes($key); ?>'] = '<?php echo addslashes($value); ?>';
    <?php endforeach; ?>
    });

</script>

<div class="col-xs-12 content">
    <?php if ($current == $semaine): ?>
        <legend><span class="text-danger"><?= __('Saisie de la semaine courante #') ?><?= $semaine ?></span></legend>
    <?php else: ?>
        <legend><?= __('Saisie de la semaine #') ?><?= $semaine ?><?= __(" de l'année ") ?><?= $annee ?></legend>
    <?php endif; ?>
    <?= $this->Form->create() ?>
        <div class="block col-xs-12">
            <div class="col-xs-2"><div class="left badge back-success"><?= h($fullNameUserAuth) ?></div></div>
            <div class="weeker right">
                <div class="left">
                    <?= $this->Html->link(__("Aujourd'hui"), ['action' => 'index'], ['class' => 'btn btn-info']) ?>
                </div>
                <div class="right">
                    <?php if ($semaine-1 < 1 ): ?>
                        <?= $this->Html->link(__('<'), ['action' => 'index', 52, $annee-1], ['class' => 'btn btn-success']) ?>
                    <?php else: ?>
                        <?= $this->Html->link(__('<'), ['action' => 'index', $semaine-1, $annee], ['class' => 'btn btn-success']) ?>
                    <?php endif; ?>
                    <?php
                        $dimanche->modify('-1 day');
                        echo("Semaine du ".$lundi->i18nFormat('dd/MM').' au '.$dimanche->i18nFormat('dd/MM'));
                    ?>
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
                    <th scope="col" class="supp"><?= $this->element('tableinfo') ?></th>
                    <th scope="col"><?= h('Client') ?></th>
                    <th scope="col"><?= h('Projet') ?></th>
                    <th scope="col"><?= h('Profil') ?></th>
                    <th scope="col"><?= h('Activité') ?></th>
                    <th scope="col"><?= h('Détails') ?></th>
                    <?php pr($lundi->setTime(2,0,0)->toUnixString());
                    pr(date('c', $holidays[4]) ); ?>
                    <th <?php echo (in_array($lundi->toUnixString(), $holidays)) ? 'class="holidays"' : 'class="semaine"'; ?> scope="col"><?= h('Lu') ?></th>
                    <?php pr($lundi->toUnixString()); ?>
                    <th <?php echo (in_array($lundi->modify('+1 days')->toUnixString(), $holidays)) ? 'class="holidays"' : 'class="semaine"'; ?> scope="col"><?= h('Ma') ?></th>
                    <?php pr($lundi->toUnixString()); ?>
                    <th <?php echo (in_array($lundi->modify('+1 days')->toUnixString(), $holidays)) ? 'class="holidays"' : 'class="semaine"'; ?> scope="col"><?= h('Me') ?></th>
                    <?php pr($lundi->toUnixString()); ?>
                    <th <?php echo (in_array($lundi->modify('+1 days')->toUnixString(), $holidays)) ? 'class="holidays"' : 'class="semaine"'; ?> scope="col"><?= h('Je') ?></th>
                    <?php pr($lundi->toUnixString()); ?>
                    <th <?php echo (in_array($lundi->modify('+1 days')->toUnixString(), $holidays)) ? 'class="holidays"' : 'class="semaine"'; ?> scope="col"><?= h('Ve') ?></th>
                    <?php pr($lundi->toUnixString()); ?>
                    <th <?php echo (in_array($lundi->modify('+1 days')->toUnixString(), $holidays)) ? 'class="holidays"' : 'class="weekend"'; ?> scope="col"><?= h('Sa') ?></th>
                    <?php pr($lundi->toUnixString()); ?>
                    <th <?php echo (in_array($lundi->modify('+1 days')->toUnixString(), $holidays)) ? 'class="holidays"' : 'class="weekend"'; ?> scope="col"><?= h('Di') ?></th>
                    <?php pr($lundi->toUnixString()); ?>
                    <?php  pr($holidays);exit; ?>
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
                            <?php echo array_key_exists($line['idc'], $clients)?$clients[$line['idc']]:$this->requestAction('Temps/getClientName/'.$line['idc']); ?>
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
                            <?php echo array_key_exists($line['idp'], $projects)?$projects[$line['idp']]:$this->requestAction('Temps/getProjectName/'.$line['idp']); ?>
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
                            <?php echo array_key_exists($line['id_profil'], $profiles)?$profiles[$line['id_profil']]:$this->requestAction('Temps/getProfilName/'.$line['id_profil']); ?>
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
                            <?php echo array_key_exists( $line['ida'] , $activities )?$activities[$line['ida']]:$this->requestAction('Temps/getActivitieName/'.$line['ida']); ?>
                        </div>
                        <?php else: ?>
                        <?php
                            echo $this->form->select('activities['.$k.']', $activities, ['value' => $line['ida'], 'class' => 'activit']);
                         ?>
                        <?php endif; ?>
                    </td>
                    <td class="cel_detail" scope="col">
                        <?php if ($validat): ?>
                            <?php echo $line['detail']; ?>
                        <?php else: ?>
                        <?php
                            echo $this->form->control("detail.$k", ['label' => false , 'value' => $line['detail']]);
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
                                echo $this->form->control("day.$k.$idDay.time", ['label' => false , 'value' => $line[$idDay]->time, 'class' => 'numericer']);
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
                    <td scope="col"></td>
                    <?php $lundi->modify('-7 days'); ?>
                    <?php foreach ($weekDays as $idDay => $value): ?>
                        <td <?php echo (in_array($lundi->modify('+1 days')->toUnixString(), $holidays)) ? 'class="holidays"' : ( ($idDay == 'Sa' || $idDay == 'Di') ? 'class="weekend"':''); ?> scope="col">
                            <div id="t<?php echo $idDay ?>" style="text-align:center; <?php if ($value>1){ echo 'color:red;'; } ?>" >
                            <?php if ($validat){ echo addslashes($value); } ?>
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
                    echo "<div class='header'>La semaine est déjà validée, correction impossible.</div>";
                }else{
                    echo $this->Form->control('validat', ['type' => 'checkbox' , 'label'=>'Valider la saisie (vérouille la saisie)']);
                }
            ?>
            </div>
            <?php
                if (!$validat) {
                    echo $this->Form->button(__('Enregistrer'), ['class'=>'right btn btn-warning']);
                }
            ?>
        </div>
    <?= $this->Form->end() ?>
</div>

<?php
    if (!$validat){
        echo $this->Html->script('ManTime/man.modal.js');
        echo $this->Html->script('ManTime/man.time.js');
    }
?>
