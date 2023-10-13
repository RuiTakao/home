<?php

/** formテンプレート設定 */
$this->Form->setTemplates([
  'inputContainer' => '{{content}}',
  'submitContainer' => '{{content}}'
]);
?>

<?php $this->start('css') ?>
<?= $this->Html->css('Admin/Posts/order') ?>
<?php $this->end() ?>

<?php $this->start('script') ?>
<?= $this->Html->script('Admin/Posts/index', ['defer']) ?>
<?php $this->end() ?>

<?= $this->Form->create(null, ['action' => 'order']) ?>
<div class="view_setting_container flex card">
  <div class="product_order_container">
    <ul id="productOrderList" class="product_order_list">
      <?php foreach ($posts as $post) : ?>

        <?php
        /** 画像の有り無し判定 */
        if ($post->image_flg) {
          $image_flg = true;
          $image_path = h($post->image_path);
          $image_name = h($post->image_name);
        } else {
          $image_flg = false;
          $image_path = 'no-image.png';
          $image_name = '画像無し';
        }
        ?>

        <li class="product_order_item">
          <div class="product js-productOrder flex justify-space-between c-pointer" draggable="true">

            <div class="product_detail_container">
              <p class="product_title"><?= $post->title ?></p>
              <?= $this->Form->radio('status[]' . $post->id, [
                ['value' => 1, 'text' => '表示'],
                ['value' => 0, 'text' => '非表示'],
              ], [
                'class' => 'product_status c-pointer',
                'value' => $post->status,
                'hiddenField' => false
              ]) ?>
              <?= $this->Form->hidden('product[]', ['value' => $post->id]) ?>
              <?= $this->Form->hidden('order[]', ['value' => $post->post_order + 1]) ?>
            </div>

            <div class="product_image_container">
              <?= $this->Html->image($image_path, ['class' => 'product_image', 'alt' =>  $image_name]) ?>
            </div>

          </div>
        </li>
      <?php endforeach; ?>
      <li class="product_order_item js-dropZone"></li>
    </ul>
  </div>
  <div class="view_setting_btn_container flex justify-center">
    <?= $this->Form->submit('この設定にする', [
      'class' => 'view_setting_btn btn danger'
    ]) ?>
  </div>
</div>
<?= $this->Form->end() ?>