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
 * @var \App\View\AppView $this
 */

$cakeDescription = 'Rontgen Tech';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>
        <?php if ($this->fetch('title')): ?>: <?= $this->fetch('title') ?><?php endif; ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('style.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 40px;
            width: auto;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .nav-link {
            background: #667eea;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background: #5a67d8;
            transform: translateY(-1px);
        }

        .logout-btn {
            background: #ff6b6b;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #ff5252;
            transform: translateY(-1px);
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .content-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        /* Override CakePHP default styles */
        .top-bar {
            display: none;
        }

        /* Flash messages styling */
        .message {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 10px;
            font-weight: 500;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .message.warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .message.info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }

            .header {
                padding: 1rem;
            }

            .nav-links {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="https://rontgen.lt/lending/img/logo_white.png" alt="Rontgen">
        </div>
        <div class="nav-links">
            <?php if ($this->request->getSession()->read('Auth.User')): ?>
                <?php $user = $this->request->getSession()->read('Auth.User'); ?>
                <?php if (isset($user['role']) && $user['role'] === 'admin'): ?>
                    <?= $this->Html->link('Admin Panel', ['controller' => 'Users', 'action' => 'index'], ['class' => 'nav-link']) ?>
                <?php else: ?>
                    <?= $this->Html->link('Dashboard', ['controller' => 'Client', 'action' => 'dashboard'], ['class' => 'nav-link']) ?>
                <?php endif; ?>
                <?= $this->Html->link(__('layout.logout'), ['controller' => 'Users', 'action' => 'logout'], ['class' => 'logout-btn']) ?>
            <?php else: ?>
                <?= $this->Html->link(__('layout.login'), ['controller' => 'Users', 'action' => 'login'], ['class' => 'nav-link']) ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="container">
        <?= $this->Flash->render() ?>
        <div class="content-card">
            <?= $this->fetch('content') ?>
        </div>
    </div>
</body>
</html>
