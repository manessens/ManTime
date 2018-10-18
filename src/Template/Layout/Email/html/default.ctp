<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
    <title><?= $this->fetch('title') ?></title>
    <?php
        echo $this->Html->css('ManTime/man_mail.css', ['fullBase' => true]);
     ?>
</head>
<body>
    <div class="header_mail">
        <?php
            echo $this->Html->image('Manonline_rouge.png', ['alt' => 'ManOnline', 'fullBase' => true]);
         ?>
     </div>
     <div class="content_mail">
         <?= $this->fetch('content') ?>
     </div>
     <div class="footer_mail">
         <p>En cas de besoin contactez le responsable ManOnline : <a href="mailto:pierre.gregorutti@manessens.com">Pierre Gregorutti</a></p>
     </div>
</body>
</html>
