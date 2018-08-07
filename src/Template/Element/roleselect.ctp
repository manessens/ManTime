<?php if ($role >= 50): ?>
    <div class="badge back-danger" >Admin</div>
<?php elseif($role >= 20): ?>
    <div class="badge back-primary" >Chef de projet</div>
<?php elseif($role >= 1): ?>
    <div class="badge back-default" >Sous-traitant</div>
<?php else: ?>
    <div class="badge back-default" >Consultant</div>
<?php endif; ?>
