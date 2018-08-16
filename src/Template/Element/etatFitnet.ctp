<?php if ($etat == 'En attente'): ?>
    <span class="badge back-info" ><?php echo $etat ?></span>
<?php elseif($etat == 'En cours'): ?>
    <span class="badge back-warning" ><?php echo $etat ?></span>
<?php elseif($etat == 'Terminé'): ?>
    <span class="badge back-success" ><?php echo $etat ?></span>
<?php elseif($etat == 'En erreur'): ?>
    <span class="badge back-danger" ><?php echo $etat ?></span>
<?php endif; ?>
