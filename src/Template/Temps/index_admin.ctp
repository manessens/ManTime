<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Temp[]|\Cake\Collection\CollectionInterface $temps
 */
?>

<script  type="text/javascript">

    var optionUsers =  [];
    var optionClients =  [];
    var optionProjects = [];
    var optionProfils  = [];
    var optionActivits = [];
    var valueUsers =  [];
    var valueClients =  [];
    var valueProjects = [];
    var valueProfils  = [];
    var valueActivits = [];

    $(function() {
    <?php foreach ($users as $key => $value): ?>
        optionUsers.push('<?php echo addslashes($key); ?>');
        valueUsers['<?php echo addslashes($key); ?>'] = '<?php echo addslashes($value); ?>';
    <?php endforeach; ?>
    <?php foreach ($clients as $key => $value): ?>
        var arrayTemp = '<?php echo addslashes($key); ?>'.split('.');
        if (optionClients.hasOwnProperty(arrayTemp[0])) {
            optionClients[arrayTemp[0]].push('<?php echo addslashes($key); ?>');
        }else{
            optionClients[arrayTemp[0]]=[];
            optionClients[arrayTemp[0]].push('<?php echo addslashes($key); ?>');
        }
        valueClients['<?php echo addslashes($key); ?>'] = '<?php echo addslashes($value); ?>';
    <?php endforeach; ?>
    <?php foreach ($projects as $key => $value): ?>
        var arrayTemp = '<?php echo addslashes($key); ?>'.split('.');
        if (optionProjects.hasOwnProperty(arrayTemp[0]+'.'+arrayTemp[1])) {
            optionProjects[arrayTemp[0]+'.'+arrayTemp[1]].push('<?php echo addslashes($key); ?>');
        }else{
            optionProjects[arrayTemp[0]+'.'+arrayTemp[1]]=[];
            optionProjects[arrayTemp[0]+'.'+arrayTemp[1]].push('<?php echo addslashes($key); ?>');
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

<div class="temps index large-10 large-10bis medium-8 columns content">
    <?php if ($current == $semaine): ?>
        <h3><?= __('Validation de la semaine courante #') ?><?= $semaine ?></h3>
    <?php else: ?>
        <legend><?= __('Validation de la semaine #') ?><?= $semaine ?><?= __(" de l'année ") ?><?= $annee ?></legend>
    <?php endif; ?>
    <?= $this->Form->create() ?>
        <div class="block col-xs-12">
            <div class="left badge back-danger"><?= h($fullNameUserAuth) ?></div>
            <div class="controler right">
                <div>
                    <?php if ($semaine-1 < 1 ): ?>
                        <?= $this->Html->link(__('<'), ['action' => 'indexAdmin', 52, $annee-1], ['class' => 'btn btn-success']) ?>
                    <?php else: ?>
                        <?= $this->Html->link(__('<'), ['action' => 'indexAdmin', $semaine-1, $annee], ['class' => 'btn btn-success']) ?>
                    <?php endif; ?>
                    <?php
                        $dimanche->modify('-1 day');
                        echo("Semaine du ".$lundi->i18nFormat('dd/MM').' au '.$dimanche->i18nFormat('dd/MM'));
                    ?>
                    <?php if ($semaine+1 > 52 ): ?>
                        <?= $this->Html->link(__('>'), ['action' => 'indexAdmin', 1, $annee+1], ['class' => 'btn btn-success']) ?>
                    <?php else: ?>
                        <?= $this->Html->link(__('>'), ['action' => 'indexAdmin', $semaine+1, $annee], ['class' => 'btn btn-success']) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="blank col-xs-12"></div>
        <table id='semainier' cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col" class="supp"><?= $this->element('tableinfo') ?></th>
                    <th scope="col"><?= h('Consultants') ?></th>
                    <th scope="col"><?= h('Client') ?></th>
                    <th scope="col"><?= h('Projet') ?></th>
                    <th scope="col"><?= h('Profil') ?></th>
                    <th scope="col"><?= h('Activité') ?></th>
                    <th scope="col"><?= h('Détails') ?></th>
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
                    $kUserOld = -1;
                ?>
                <?php foreach ($week as $kUser => $weekUser): ?>
                    <?php foreach ($weekUser as $k => $line): ?>
                    <tr id="<?php echo $line['nline'] ?>" user="<?php echo $kUser ?>"
                            <?php if ($kUserOld != $kUser): ?> class="newUser" <?php $kUserOld = $kUser; ?><?php endif; ?>>
                        <td scope="col" class="actions">
                            <?php if (!$validat): ?>
                                <button type="button" class="btn btn-danger remove">-</button>
                            <?php endif; ?>
                        </th>
                        <td scope="col" class="cel_users">
                            <?php if ($validat): ?>
                            <div>
                                <?php echo $users[$kUser]; ?>
                            </div>
                            <?php else: ?>
                            <?php
                                echo $this->form->select('users['.$kUser.']['.$k.']', $users, ['value' => $kUser, 'class' => 'users']);
                             ?>
                            <?php endif; ?>
                        </td>
                        <td scope="col" class="cel_client">
                            <?php if ($validat): ?>
                            <div>
                                <?php
                                    if (array_key_exists($line['idc'], $clients)) {
                                        echo $clients[$line['idc']];
                                    }else{
                                        echo $this->requestAction('Temps/getClientName/'.$line['idc']);
                                    }
                                ?>
                            </div>
                            <?php else: ?>
                            <?php
                                echo $this->form->select('client['.$kUser.']['.$k.']', $clients, ['value' => $line['idc'], 'class' => 'client']);
                             ?>
                            <?php endif; ?>
                        </td>
                        <td scope="col" class="cel_projet">
                            <?php if ($validat): ?>
                            <div>
                                <?php
                                    if (array_key_exists($line['idp'], $projects)) {
                                        echo $projects[$line['idp']];
                                    }else{
                                        echo $this->requestAction('Temps/getProjectName/'.$line['idp']);
                                    }
                                ?>
                            </div>
                            <?php else: ?>
                            <?php
                                echo $this->form->select('projet['.$kUser.']['.$k.']', $projects, ['value' => $line['idp'], 'class' => 'project']);
                             ?>
                            <?php endif; ?>
                        </td>
                        <td scope="col" class="cel_profil">
                            <?php if ($validat): ?>
                            <div>
                                <?php
                                    if (array_key_exists($line['id_profil'], $profiles)) {
                                        echo $profiles[$line['id_profil']];
                                    }else{
                                        echo $this->requestAction('Temps/getProfilName/'.$line['id_profil']);
                                    }
                                ?>
                            </div>
                            <?php else: ?>
                            <?php
                                echo $this->form->select('profil['.$kUser.']['.$k.']', $profiles, ['value' => $line['id_profil'], 'class' => 'profil']);
                             ?>
                            <?php endif; ?>
                        </td>
                        <td scope="col" class="cel_activit">
                            <?php if ($validat): ?>
                            <div>
                                <?php
                                    if (array_key_exists($line['ida'], $activities)) {
                                        echo $activities[$line['ida']];
                                    }else{
                                        echo $this->requestAction('Temps/getActivitieName/'.$line['ida']);
                                    }
                                ?>
                            </div>
                            <?php else: ?>
                            <?php
                                echo $this->form->select('activities['.$kUser.']['.$k.']', $activities, ['value' => $line['ida'], 'class' => 'activit']);
                             ?>
                            <?php endif; ?>
                        </td>
                        <td class="cel_detail" scope="col">
                            <?php if ($validat): ?>
                                <?php echo $line['detail']; ?>
                            <?php else: ?>
                            <?php
                                echo $this->form->control("detail.$kUser.$k", ['label' => false , 'value' => $line['detail']]);
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
                                    echo $this->Form->hidden("day.$kUser.$k.$idDay.id", ['label' => false , 'value' => $line[$idDay]->idt]);
                                    echo $this->form->control("day.$kUser.$k.$idDay.time", ['label' => false , 'value' => $line[$idDay]->time]);
                                 ?>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
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
                    <td scope="col"></td>
                    <?php foreach ($weekDays as $idDay => $value): ?>
                        <td scope="col">
                            <div id="t<?php echo $idDay ?>" style="text-align:center;" >
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
                echo $this->Form->control('validat', ['type' => 'checkbox' , 'value' => !$validat,'label'=>"Valider la semaine (autorise l'export)"]);
            ?>
            </div>
        <?= $this->Form->button(__('Enregistrer'), ['class'=>'right btn btn-warning']) ?>
        </div>
    <?= $this->Form->end() ?>
</div>

<?php
    if (!$validat){
        echo $this->Html->script('ManTime/man.modal.js');
        echo $this->Html->script('ManTime/man.time-admin.js');
    }
?>
