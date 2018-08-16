<?php if ($etat == ?> <?= Configure::read('fitnet.wait'); ?> <?php ): ?>
    <span class="badge back-info" ><?php echo $etat ?></span>
<?php elseif($etat == Configure::read('fitnet.run')): ?>
    <span class="badge back-warning" ><?php echo $etat ?></span>
<?php elseif($etat == Configure::read('fitnet.end')): ?>
    <span class="badge back-success" ><?php echo $etat ?></span>
<?php elseif($etat == Configure::read('fitnet.err')): ?>
    <span class="badge back-danger" ><?php echo $etat ?></span>
<?php endif; ?>
