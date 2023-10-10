<?php

/** css読み込み */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('Admin/Posts/index') ?>
<?php $this->end() ?>

<?php $this->start('script') ?>
<?= $this->Html->script('Admin/Posts/index', ['defer']) ?>
<?php $this->end() ?>

<?php /** 作品のコンテンツリスト */ ?>
<?= $this->Form->create(null, ['action' => 'admin/posts/order']) ?>
<ul id="productOrderList" class="product_list">
  <?php foreach ($posts as $post) : ?>

    <?php
    /** 画像の有り無し判定 */
    if (!$post->image_flg) {
      $image_flg = true;
      $image_path = h($post->image_path);
      $image_name = h($post->image_name);
    } else {
      $image_flg = false;
      $image_path = 'no-image.png';
      $image_name = '画像無し';
    }

    /** ステータスの判定 */
    if ($post->status) {
      $status = '表示';
      $stats_class = 'status success';
    } else {
      $status = '非表示';
      $stats_class = 'status danger';
    }

    /** URLの有り無し判定 */
    if (!$post->url_flg) {
      $url = h($post->url);
      $url = $this->Html->link($url, $url, ['class' => 'product_access_link', 'target' => '_blank']);
    } else {
      $url = 'なし';
    }
    ?>

    <?php /** 作品のコンテンツ */ ?>
    <li class="product">
      <div class="product_container flex card js-productOrder" draggable="true">

        <?php /** 作品の画像 */ ?>
        <div class="product_image_container flex_column">
          <?= $this->Html->image($image_path, ['class' => 'product_image', 'alt' => $image_name]) ?>
        </div>

        <?php /** 作品の詳細 */ ?>
        <div class="product_detail_container flex_column">
          <p class="product_title"><?= h($post->title) ?><span class="product_status ml8 <?= $stats_class ?>"><?= $status ?></span></p>
          <?php /** 画像の有り無し判定 */ ?>
          <?php if ($image_flg) : ?>
            <p class="product_image_name mt16"><span class="title">画像名</span><?= $image_name ?></p>
          <?php endif; ?>
          <p class="product_url mt8"><span class="title">ＵＲＬ</span><?= $url ?></p>
          <p class="product_body mt16"><?= h($post->body) ?></p>
          <?= $this->Html->link('編集する', ['controller' => 'posts', 'action' => 'edit', $post->id], ['class' => 'product_edit_btn btn danger mt32']) ?>
        </div>
        <?= $this->Form->hidden('product[]', ['value' => $post->id]) ?>
        <?= $this->Form->hidden('order[]', ['value' => $post->post_order - 1]) ?>
      </div>
    </li>
  <?php endforeach; ?>
  <li class="product js-dropZone"></li>
</ul>
<div id="b">bbbb</div>
<?= $this->Form->submit("順番入れ替え") ?>
<?= $this->Form->end() ?>