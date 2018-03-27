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

$cakeDescription = 'ManTime - saisie des temps simplifié';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?> :
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?php echo $this->Html->css('Bootstrap/bootstrap.css'); ?>
    <?php echo $this->Html->css('ManTime/man.css'); ?>
    <?php echo $this->Html->script(['jquery-3.3.1.min.js', 'Bootstrap/bootstrap.js', 'moment.js']); ?>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <nav class="top-bar expanded" data-topbar role="navigation">
        <ul class="title-area large-2 large-2bis medium-4 columns">
            <li class="name">
                <!-- <h1><a href=""><?= $this->fetch('title') ?></a></h1> -->
                <h1><?= $this->Html->link(__('ManTimes'), ['controller' => 'Board','action' => 'index']) ?></h1>
            </li>
        </ul>
        <div class="top-bar-section">
            <ul class="right">
                <li><?= $this->Html->link(__('Profil'), ['controller' => 'Users', 'action' => 'profil']) ?></li>
                <li><?= $this->Html->link(__('Déconnexion'), ['controller' => 'Users', 'action' => 'logout']) ?></li>
                <!-- <li><a target="_blank" href="https://book.cakephp.org/3.0/">Documentation</a></li> -->
                <!-- <li><a target="_blank" href="https://api.cakephp.org/3.0/">API</a></li> -->
            </ul>
        </div>
    </nav>
    <?= $this->Flash->render() ?>
    <div class="clearfix col-xs-12 center">
        <?php if( $this->request->session()->read('Auth.User.role') >= 50 && !isset($controller) ): ?>
            <?= $this->element('menuleft') ?>
        <?php endif; ?>
        <?= $this->fetch('content') ?>
    </div>
    <footer>
    </footer>
</body>
</html>
