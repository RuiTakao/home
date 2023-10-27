<?php

/** css読み込み */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('Admin/Posts/index') ?>
<?php $this->end() ?>

<?php /** 作品のコンテンツリスト */ ?>
<ul class="product_list">
  <?php foreach ($posts as $post) : ?>

    <?php
    /** 画像の有り無し判定 */
    if ($post->image_flg) {
      $image_flg = true;
      $image_alt_name = h($post->image_alt_name);
    } else {
      $image_flg = false;
      $image_alt_name = '画像無し';
    }

    /** ステータスの判定 */
    if ($post->product_view_flg) {
      $product_view_flg = '表示';
      $product_view_flg_class = 'status success';
    } else {
      $product_view_flg = '非表示';
      $product_view_flg_class = 'status danger';
    }

    /** URLの有り無し判定 */
    if ($post->url_flg) {
      $url_path = h($post->url_path);
      $url_path = $this->Html->link($url_path, $url_path, ['class' => 'product_access_link', 'target' => '_blank']);
    } else {
      $url_path = 'なし';
    }
    ?>

    <?php /** 作品のコンテンツ */ ?>
    <li class="product flex card justify-space-between">

      <?php /** 作品の画像 */ ?>
      <div class="product_image_container flex align-center">
        <?= $this->Html->image($post->image_path, ['class' => 'product_image', 'alt' => $image_alt_name]) ?>
      </div>

      <?php /** 作品の詳細 */ ?>
      <div class="product_detail_container">
        <p class="product_title"><?= h($post->product_name) ?><span class="product_status ml8 <?= $product_view_flg_class ?>"><?= $product_view_flg ?></span></p>
        <?php /** 画像の有り無し判定 */ ?>
        <?php if ($image_flg) : ?>
          <p class="product_image_name mt16"><span class="title">画像名</span><?= $image_alt_name ?></p>
        <?php endif; ?>
        <p class="product_url mt8"><span class="title">ＵＲＬ</span><?= $url_path ?></p>
        <p class="product_body mt16"><?= h($post->product_detail) ?></p>
        <?= $this->Html->link('編集する', ['controller' => 'posts', 'action' => 'edit', $post->id], ['class' => 'product_edit_btn btn danger mt32']) ?>
      </div>

    </li>

  <?php endforeach; ?>

</ul>