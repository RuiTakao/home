<!DOCTYPE html>
<html>

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>たかおの作品リスト</title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->fetch('meta') ?>

    <!-- css -->
    <?php /** css */ ?>
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">
    <?= $this->Html->css(['reset']) ?>
    <?= $this->Html->css(['utility']) ?>
    <?= $this->Html->css(['Admin/header']) ?>
    <?php /** css 各ページ */ ?>
    <?= $this->fetch('css') ?>

    <?php /** js */ ?>
    <?php if ($this->fetch('script')) : ?>
        <!-- js -->
        <?= $this->fetch('script') ?>
    <?php endif; ?>
</head>

<body>
    <header class="header">
        <div class="header_container container flex justify-space-between align-center">
            <h1 class="header_title">takaoFollio 管理画面</h1>
            <?php if (!is_null($this->request->getAttribute('identity'))) : ?>
                <?= $this->Html->link('ログアウト', ['controller' => 'users', 'action' => 'logout'], ['class' => 'logout flex justify-center align-center']) ?>
            <?php endif; ?>
        </div>
    </header>
    <aside class="aside">
        <nav class="aside_nav">
            <ul class="aside_link_list">
                <li class="aside_link_item"><?= $this->Html->link('作品一覧', ['controller' => 'posts', 'action' => 'index'], ['class' => 'aside_link']) ?></li>
                <li class="aside_link_item"><?= $this->Html->link('作品の追加', ['controller' => 'posts', 'action' => 'add'], ['class' => 'aside_link']) ?></li>
                <li class="aside_link_item"><?= $this->Html->link('表示の設定', ['controller' => 'posts', 'action' => 'order'], ['class' => 'aside_link']) ?></li>
                <li class="aside_link_item"><a class="aside_link" href="#">プロフ設定</a></li>
            </ul>
        </nav>
    </aside>
    <main class="main">
        <div class="main_container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
</body>

</html>