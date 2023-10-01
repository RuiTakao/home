<?php

/** 管理画面であるかないか判定 */
$is_admin = false;
$admin_header = '';
$admin_main = '';
if ($this->request->getParam('prefix') == 'Admin' && $this->request->getParam('action') != 'login') {
    $is_admin = true;
    $admin_header = ' admin_header';
    $admin_main = ' admin_main';
}
?>
<!DOCTYPE html>
<html>

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>たかおの作品リスト</title>
    <?= $this->Html->meta('icon') ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">

    <?= $this->Html->css(['home']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>

<body>
    <header class="header<?= $admin_header ?>">
        <div class="container">
            <h1 class="header_title">たかおの作品リスト</h1>
            <?php if ($is_admin) : ?>
                <div>
                    <?= $this->Html->link('追加する', ['controller' => 'posts', 'action' => 'add']) ?>
                    <?= $this->Html->link('ログアウト', ['controller' => 'users', 'action' => 'logout']) ?>
                </div>
            <?php endif; ?>
        </div>
    </header>
    <main class="main<?= $admin_main ?>">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <footer>
    </footer>
</body>

</html>
