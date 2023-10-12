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
    'layout/header',
    'layout/main',
    'Posts/index'
  ]) ?>
</head>

<body>
  <header class="header">
    <div class="header_container">
      <h1 class="header_title">たかおの作品リスト</h1>
    </div>
  </header>
  <main class="main">
    <div class="main_container">
      <?php /** 作品のコンテンツリスト */ ?>
      <ul class="product_list">
        <?php foreach ($posts as $post) : ?>

          <?php
          /** 画像の有り無し判定 */
          if ($post->image_flg) {
            $image_path = h($post->image_path);
            $image_name = $post->image_name;
          } else {
            $image_path = 'no-image.png';
            $image_name = '画像はありません';
          }
          ?>

          <?php /** 作品のコンテンツ */ ?>
          <li class="product flex justify-space-between">

            <?php /** 作品の画像 */ ?>
            <div class="product_image_container">
              <?= $this->Html->image($image_path, ['class' => 'product_image', 'alt' => $image_name]) ?>
            </div>

            <?php /** 作品の詳細 */ ?>
            <div class="product_detail_container">
              <p class="product_title"><?= h($post->title) ?></p>
              <p class="product_body mt16"><?= h($post->body) ?></p>

              <?php /** リンクの有り無し判定 */ ?>
              <?php if ($post->url_flg) : ?>
                <?= $this->Html->link('アプリを見る', h($post->url), ['class' => 'product_access_btn btn info mt32', 'target' => '_blank']) ?>
              <?php endif; ?>
            </div>

          </li>

        <?php endforeach; ?>
      </ul>
    </div>
  </main>
</body>

</html>