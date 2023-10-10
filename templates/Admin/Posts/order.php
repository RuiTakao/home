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
        <li class="product_order_item">
          <div class="product js-productOrder" draggable="true">
            <p><?= $post->title ?></p>
            <?= $this->Form->radio('status[]' . $post->id, [
              ['value' => 1, 'text' => '表示'],
              ['value' => 0, 'text' => '非表示'],
            ], [
              'value' => $post->status,
              'hiddenField' => false
            ]) ?>
            <?= $this->Form->hidden('product[]', ['value' => $post->id]) ?>
            <?= $this->Form->hidden('order[]', ['value' => $post->post_order + 1]) ?>
          </div>
        </li>
      <?php endforeach; ?>
      <li class="product_order_item js-dropZone"></li>
    </ul>
  </div>
  <div class="right">
    <?= $this->Form->submit('この設定にする') ?>
  </div>
</div>
<?= $this->Form->end() ?>