<?php if ($role >= 50): ?>
    <span class="text-danger">Admin</span>
<?php elseif($role >= 20): ?>
    <span class="text-primary">Chef de projet</span>
<?php else: ?>
    <span>Consultant</span>
<?php endif; ?>
