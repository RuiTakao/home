<?php

/** 管理画面であるかないか判定 */
$is_admin = false;
if ($this->request->getParam('prefix') == 'Admin' && $this->request->getParam('action') != 'login') {
    $is_admin = true;
}
?>
<!DOCTYPE html>
<html>

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>たかおの作品リスト</title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->fetch('meta') ?>
    
    <?php /** css */ ?>
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">
    <?= $this->Html->css(['base/reset']) ?>
    <?= $this->Html->css(['base/utility']) ?>
    <?= $this->Html->css(['base/header']) ?>
    <?= $this->Html->css(['base/main']) ?>
    <?php if ($is_admin) : ?>
        <?= $this->Html->css(['base/Admin/header']) ?>
    <?php endif; ?>
    <?= $this->fetch('css') ?>

    <?php /** js */ ?>
    <?= $this->fetch('script') ?>
</head>

<body>
    <header class="header">
        <div class="header_container">
            <h1 class="header_title">たかおの作品リスト</h1>
            <?php if ($is_admin) : ?>
                <div>
                    <?= $this->Html->link('追加する', ['controller' => 'posts', 'action' => 'add']) ?>
                    <?= $this->Html->link('ログアウト', ['controller' => 'users', 'action' => 'logout']) ?>
                </div>
            <?php endif; ?>
        </div>
    </header>
    <main class="main">
        <div class="main_container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
</body>

</html>