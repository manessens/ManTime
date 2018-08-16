<?php if ($etat == \Cake\Core\Configure::read('fitnet.wait') ): ?>
    <span class="badge back-info" ><?php echo $etat ?></span>
<?php elseif($etat == \Cake\Core\Configure::read('fitnet.run')): ?>
    <span class="badge back-warning" ><?php echo $etat ?></span>
<?php elseif($etat == \Cake\Core\Configure::read('fitnet.end')): ?>
    <span class="badge back-success" ><?php echo $etat ?></span>
<?php elseif($etat == \Cake\Core\Configure::read('fitnet.err')): ?>
    <span class="badge back-danger" ><?php echo $etat ?></span>
<?php endif; ?>
