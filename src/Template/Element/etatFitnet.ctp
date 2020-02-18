<?php if ($etat == \Cake\Core\Configure::read('vsa.err') ): ?>
    <span class="badge back-info" ><?php echo $etat ?></span>
<?php elseif($etat == \Cake\Core\Configure::read('fitnet.run')): ?>
    <span class="badge back-warning" ><?php echo $etat ?></span>
<?php elseif($etat == \Cake\Core\Configure::read('vsa.end')): ?>
    <span class="badge back-success" ><?php echo $etat ?></span>
<?php elseif($etat == \Cake\Core\Configure::read('vsa.err')): ?>
    <span class="badge back-danger" ><?php echo $etat ?></span>
<?php elseif($etat == \Cake\Core\Configure::read('vsa.nerr')): ?>
    <span class="badge back-default" ><?php echo $etat ?></span>
<?php endif; ?>
