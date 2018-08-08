<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="message success alert-info" onclick="this.classList.add('hidden')"><?= $message ?></div>
