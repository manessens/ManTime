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
                <td scope="col" class="cel_client">
                    <?php
                        $clients[$line['idc']]);
                     ?>
                </td>
                <td scope="col" class="cel_projet">
                    <?php
                        $projects[$line['idp']]);
                     ?>
                </td>
                <td scope="col" class="cel_profil">
                    <?php
                        $profiles[$line['id_profil']]);
                     ?>
                </td>
                <td scope="col" class="cel_activit">
                    <?php
                        $activities[$line['ida']]);
                     ?>
                </td>
                <td scope="col">
                    <?php $line['Lu']->time ?>
                </td>
                <td scope="col">
                    <?php $line['Ma']->time ?>
                </td>
                <td scope="col">
                    <?php $line['Me']->time ?>
                </td>
                <td scope="col">
                    <?php $line['Je']->time ?>
                </td>
                <td scope="col">
                    <?php $line['Ve']->time ?>
                </td>
                <td scope="col">
                    <?php $line['Sa']->time ?>
                </td>
                <td scope="col">
                    <?php $line['Di']->time ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr id="total">
                <td scope="col"></td>
                <td scope="col"></td>
                <td scope="col"></td>
                <td scope="col"></td>
                <td scope="col"><div id="tLu"></div></td>
                <td scope="col"><div id="tMa"></div></td>
                <td scope="col"><div id="tMe"></div></td>
                <td scope="col"><div id="tJe"></div></td>
                <td scope="col"><div id="tVe"></div></td>
                <td scope="col"><div id="tSa"></div></td>
                <td scope="col"><div id="tDi"></div></td>
            </tr>
        </tbody>
    </table>
    <div class='right col-xs-5'>
        <div class = 'left'>
            <div class='header'>La semaine à déjà été validé. Modification impossible.</div>
        </div>
    </div>
</div>

<?php echo $this->Html->script('ManTime/man.modal.js'); ?>
<?php echo $this->Html->script('ManTime/man.time.js'); ?>
