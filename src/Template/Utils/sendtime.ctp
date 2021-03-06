<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>

<div class="col-xs-12 new_content content">
    <legend>
        <?= $this->Html->link(__('<'), ['action' => 'index'], ['class' => 'btn btn-info']) ?> <?= __('Utilitaire - ') ?><span class="text-danger"><?= __('soumission personalisée') ?></span> semaine #<span id='nsemaine'><?php echo $semaine ?></span> - <span id="nannee"><?php echo $annee ?></span>
    </legend>
    <div class="col-xs-10">
        <form method="post" action="/utils/sendtime">
            <div class=" left ">
                <div class="select-week"><input style="width:250px;" type="week" name="select-week" id="select-week" min="2018-W15" value="<?php echo $annee ?>-W<?php echo $semaine ?>" ></div>
                <div class="block" id="select-weekandyear" >
                    <div class="col-xs-3 week"><input style="width:71px;" type="number" name="week" id="week" value="<?php echo $semaine ?>" ></div>
                    <div class="col-xs-6 year"><input style="width:180px;" type="number" name="year" id="year" value="<?php echo $annee ?>" ></div>
                </div>
            </div>
            <div class="col-xs-3"><button class="btn btn-primary" type="submit" >Actualiser </button></div>
        </form>
        <div>
            <table>
                <thead>
                    <tr>
                        <td colspan="2" class="alert-danger" > Consultant en attente de soumission </td>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($usersN) == 0): ?>
                        <tr><td colspan="2">0 - Consultants en attente</td></tr>
                    <?php endif; ?>
                    <?php foreach ($usersN as $userN): ?>
                        <tr>
                            <td><?php echo $userN->fullname ?></td>
                            <td>
                                <button type="button" data-activ="Activ" data-idu="<?php echo $userN->idu ?>" class="btn btn-success btn-loader">+</button>
                                <div class="loader btn" style="display:none;" id="loader-<?php echo $userN->idu ?>"> </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <table>
                <thead>
                    <tr>
                        <td colspan="2" class="alert-success" > Consultant ayant soumis leur saisie </td>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($usersV) == 0): ?>
                        <tr>
                            <tr><td colspan="2">0 - Consultants n'a encore soumis sa semaine</td></tr>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($usersV as $userV): ?>
                        <tr>
                            <td><?php echo $userV->fullname ?></td>
                            <td>
                                <button type="button" data-activ="Unactiv" data-idu="<?php echo $userV->idu ?>" class="btn btn-danger btn-loader">-</button>
                                <div class="loader btn" style="display:none;" id="loader-<?php echo $userV->idu ?>"> </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>

    </div>
</div>

<?php
    echo $this->Html->css('ManTime/man.loader.css');
    echo $this->Html->script('ManTime/man.utils.js');
?>
