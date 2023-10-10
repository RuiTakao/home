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
  <?= $this->Html->css(['reset']) ?>
  <?= $this->Html->css(['utility']) ?>
  <?= $this->Html->css(['base/header']) ?>
  <?= $this->Html->css(['base/main']) ?>
  <?php /** css 各ページ */ ?>
  <?= $this->fetch('css') ?>

  <?php /** js */ ?>
  <?php if ($this->fetch('script')) : ?>
    <!-- js -->
    <script>

    </script>
    <?= $this->fetch('script') ?>
  <?php endif; ?>
</head>

<body>
  <header class="header">
    <div class="header_container">
      <h1 class="header_title">たかおの作品リスト</h1>
    </div>
  </header>
  <main class="main">
    <div class="main_container">
      <?= $this->fetch('content') ?>
    </div>
  </main>
</body>

</html>