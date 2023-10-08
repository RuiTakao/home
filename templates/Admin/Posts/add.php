<?php

/** formテンプレート設定 */
$this->Form->setTemplates([
  'inputContainer' => '{{content}}',
  'submitContainer' => '{{content}}'
]);
?>


<?php /** css読み込み */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('Admin/dropify/css/dropify.min.css') ?>
<?= $this->Html->css('Admin/Posts/add.css') ?>
<?php $this->end() ?>

<?php /** js読み込み */ ?>
<?php $this->start('script') ?>
<?= $this->Html->script('Admin/dropify.js', ['defer']) ?>
<?php $this->end() ?>

<?php /** form */ ?><?= $this->Form->create($post, [
  'id' => 'product_upload_form',
  'class' => 'product_upload_form',
  'type' => 'file'
]) ?>
<ul class="product_upload_content_list card">

  <?php /** title */ ?>
  <li class="product_upload_content">
    <label class="product_upload_content_title fw600 pt4" for="title">作品名</label>
    <div class="product_upload_content_input">
      <?= $this->Form->control('title', [
        'id' => 'title',
        'class' => 'input',
        'label' => false,
        'required' => false
      ]) ?>
    </div>
  </li>

  <li class="product_upload_content">
    <div class="product_upload_content_title fw600 pt4"><label for="body">説明</label></div>
    <div class="product_upload_content_input">
      <?= $this->Form->control('body', [
        'id' => 'body',
        'class' => 'textarea',
        'type' => 'textarea',
        'label' => false,
        'required' => false
      ]) ?>
    </div>
  </li>

  <li class="product_upload_content">
    <div class="product_upload_content_title fw600"><label for="status">ステータス</label></div>
    <div class="product_upload_content_input form_list_radio">
      <?= $this->Form->radio('status', [
        ['value' => 1, 'text' => '表示'],
        ['value' => 0, 'text' => '非表示']
      ]) ?>
    </div>
  </li>

  <li class="product_upload_content">
    <div class="product_upload_content_title fw600 pt4"><label for="url">ＵＲＬ</label></div>
    <div class="product_upload_content_input">
      <?= $this->Form->control('url', [
        'id' => 'url',
        'class' => 'input',
        'label' => false,
        'required' => false
      ]) ?>
      <div class="form_list_checkbox mt16">
        <?= $this->Form->checkbox('url_flg', [
          'id' => 'url_flg'
        ]) ?><label for="url_flg">なし</label>
      </div>
    </div>
  </li>

  <li class="product_upload_content">
    <div class="product_upload_content_title fw600 pt4"><label for="url">画像名</label></div>
    <div class="product_upload_content_input">
      <?= $this->Form->control('image_name', [
        'id' => 'image_name',
        'class' => 'input',
        'label' => false,
        'required' => false
      ]) ?>
      <div class="form_list_checkbox mt16">
        <?= $this->Form->checkbox('image_flg', [
          'id' => 'image_flg'
        ]) ?><label for="image_flg">なし</label>
      </div>
      <?= $this->Form->control('image_path', [
        'class' => 'dropify',
        'type' => 'file',
        'label' => false,
        'required' => false,
        'div' => true
      ]) ?>
    </div>
  </li>
  <?php if ($post->getError('image_path')) : ?>
    <p class="error-message"><?= $post->getError('image_path')['_empty'] ?></p>
  <?php endif; ?>

  <li class="product_upload_content">
    <div></div>
    <div class="product_upload_content_input">
      <?= $this->Form->button('登録する', [
        'class' => 'btn btn_danger',
        'style' => 'width:100%;'
      ]) ?>
    </div>
  </li>

</ul>
<?= $this->Form->end() ?>