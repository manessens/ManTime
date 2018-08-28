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
        <form method="post" action="/utils/index">
            <div class=" left ">
                <div class="select-week"><input style="width:250px;" type="week" name="select-week" id="select-week" min="2018-W15" value="<?php echo $annee ?>-W<?php echo $semaine ?>" ></div>
                <div class="block">
                    <div class="week"><input style="width:71px;" type="number" name="week" id="week" value="<?php echo $semaine ?>" ></div>
                    <div class="year"><input style="width:180px;" type="number" name="year" id="year" value="<?php echo $annee ?>" ></div>
                </div>
                <div class="col-xs-3"><button class="btn btn-primary" type="submit" >Actualiser </button></div>
            </div>
        </form>
        <div>

        </div>


    </div>
</div>
