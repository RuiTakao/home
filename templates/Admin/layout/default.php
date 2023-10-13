<!DOCTYPE html>
<html>

<head>
  <?= $this->Html->charset() ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>たかおの作品リスト</title>
  <?= $this->Html->meta('icon') ?>
  <?= $this->fetch('meta') ?>

  <!-- css -->
  <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">
  <?= $this->Html->css([
    'reset',
    'utility',
    'Admin/layout/header',
    'Admin/layout/aside',
    'Admin/layout/main'
  ]) ?>
  <?php /** css 各ページ */ ?>
  <?= $this->fetch('css') ?>

  <?php /** js */ ?>
  <?php if ($this->fetch('script')) : ?>
    <!-- js -->
    <?= $this->fetch('script') ?>
  <?php endif; ?>
</head>

<body>
  <header class="header w100 h64">
    <div class="header_container">
      <h1 class="header_title">TakaoFollio 管理画面</h1>
      <?= $this->Html->link('ログアウト', ['controller' => 'users', 'action' => 'logout'], ['class' => 'logout']) ?>
    </div>
  </header>
  <aside class="aside">
    <nav class="aside_nav">
      <ul class="aside_link_list">
        <?php
        $param = $this->request->getParam('action');
        function asideLink($param, $link)
        {
          if ($param == 'edit') {
            $param = 'index';
          }

          if ($param == $link) {
            return ' is-active';
          }
          return '';
        }
        ?>
        <li class="aside_link_item"><?= $this->Html->link('作品一覧', ['controller' => 'posts', 'action' => 'index'], ['class' => 'aside_link' . asideLink($param, 'index')]) ?></li>
        <li class="aside_link_item"><?= $this->Html->link('作品の追加', ['controller' => 'posts', 'action' => 'add'], ['class' => 'aside_link' . asideLink($param, 'add')]) ?></li>
        <li class="aside_link_item"><?= $this->Html->link('表示の設定', ['controller' => 'posts', 'action' => 'order'], ['class' => 'aside_link' . asideLink($param, 'order')]) ?></li>
        <li class="aside_link_item"><a class="aside_link" href="#">プロフ設定</a></li>
      </ul>
    </nav>
  </aside>
  <main class="main">
    <div class="main_container">
      <?= $this->fetch('content') ?>
    </div>
  </main>
  <?= $this->element('flash/message') ?>
</body>

</html>