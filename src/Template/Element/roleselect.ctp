<?php if ($role >= \Cake\Core\Configure::read('role.admin')): ?>
    <div class="badge back-danger" >Admin</div>
<?php elseif($role >= \Cake\Core\Configure::read('role.cp')): ?>
    <div class="badge back-primary" >Chef de projet</div>
<?php elseif($role >= \Cake\Core\Configure::read('role.extern')): ?>
    <div class="badge back-default" >Sous-traitant</div>
<?php else: ?>
    <div class="badge back-default" >Consultant</div>
<?php endif; ?>
