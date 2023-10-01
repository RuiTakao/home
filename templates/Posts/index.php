<?php $this->start('css') ?>
<?= $this->Html->css('home') ?>
<?php $this->end() ?>

<ul class="product_content_list">
  <?php foreach ($posts as $post) : ?>
    <li class="product_content flex">
      <div class="product_image_content flex_column">
        <?= $this->Html->image(h($post->image_path), ['class' => 'product_image', 'alt' => 'データベースからalt取得']) ?>
      </div>
      <div class="product_detail_content flex_column">
        <p class="product_title"><?= h($post->title) ?></p>
        <p class="product_body mt16"><?= h($post->body) ?></p>
        <?= $this->Html->link('アプリを見る', h($post->url), ['class' => 'product_access_link btn btn_info mt32', 'target' => '_blank']) ?>
      </div>
    </li>
  <?php endforeach; ?>
  </li>
</ul>