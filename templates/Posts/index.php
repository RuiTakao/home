<?php /** css読み込み */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('home') ?>
<?php $this->end() ?>

<?php /** 作品のコンテンツリスト */ ?>
<ul class="product_list">
  <?php foreach ($posts as $post) : ?>

    <?php /** 作品のコンテンツ */ ?>
    <li class="product flex">

      <?php
      /** 画像の有り無し判定 */
      if (!$post->image_flg) {
        $image_path = h($post->image_path);
        $image_name = $post->image_name;
      } else {
        $image_path = 'no-image.png';
        $image_name = '画像はありません';
      }
      ?>

      <?php /** 作品の画像 */ ?>
      <div class="product_image_container flex_column">
        <?= $this->Html->image($image_path, ['class' => 'product_image', 'alt' => $image_name]) ?>
      </div>

      <?php /** 作品の詳細 */ ?>
      <div class="product_detail_container flex_column">
        <p class="product_title"><?= h($post->title) ?></p>
        <p class="product_body mt16"><?= h($post->body) ?></p>

        <?php /** リンクの有り無し判定 */ ?>
        <?php if (!$post->url_flg) : ?>
          <?= $this->Html->link('アプリを見る', h($post->url), ['class' => 'product_access_btn btn info mt32', 'target' => '_blank']) ?>
        <?php endif; ?>
      </div>

    </li>

  <?php endforeach; ?>
</ul>