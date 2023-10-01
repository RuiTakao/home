<?php $this->start('css') ?>
<?= $this->Html->css('Admin/Posts/index') ?>
<?php $this->end() ?>

<ul class="product_content_list">
  <?php foreach ($posts as $post) : ?>
    <li class="product_content flex card">
      <div class="product_image_content flex_column">
        <?= $this->Html->image(h($post->image_path), ['class' => 'product_image', 'alt' => 'CakePHP']) ?>
      </div>
      <div class="product_detail_content flex_column">
        <p class="product_title"><?= h($post->title) ?><span class="product_status status status_success ml8">表示</span></p>
        <p class="product_image_name mt16"><span class="fw600 mr"><span class="name">画像名</span>：</span><?= h($post->title) ?></p>
        <p class="product_url mt8"><span class="fw600 mr"><span class="name">ＵＲＬ</span>：</span><?= $this->Html->link(h($post->url), h($post->url), ['class' => 'product_access_link', 'target' => '_blank']) ?></p>
        <p class="product_body mt16"><?= h($post->body) ?></p>
        <?= $this->Html->link('編集する', ['controller' => 'posts', 'action' => 'edit', $post->id], ['class' => 'product_edit_link btn btn_danger mt32']) ?>
      </div>
    </li>
  <?php endforeach; ?>
  </li>
</ul>