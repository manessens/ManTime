<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>

<div class="col-xs-12 new_content content">
    <legend>
        <?= __('Utilitaire - ') ?><span class="text-danger"><?= __('soumission personalisé des semaines') ?></span>
    </legend>
    <div class="col-xs-10">
        <div><input type="week" name="select-week" id="select-week" min="2018-W15" value="<?php echo $year ?>-W<?php echo $semaine ?>" ></div>
        <div><input type="number" name="week" id="week" value="<?php echo $semaine ?>" ></div>
        <div><input type="number" name="year" id="year" value="<?php echo $year ?>" ></div>
    </div>


</div>
