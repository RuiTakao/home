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
<?= $this->Html->script('Admin/Posts/add.js', ['defer']) ?>
<?php $this->end() ?>

<?php /** form */ ?>
<?= $this->Form->create($post, [
  'id' => 'add_product_form',
  'class' => 'add_product_form',
  'type' => 'file'
]) ?>
<div class="add_product_form_container card">
  <table class="add_product_table">
    <tr class="add_product_table_row">
      <th class="add_product_table_head">
        <label class="add_product_form_title" for="title">作品名</label>
      </th>
      <td class="add_product_table_data">
        <?= $this->Form->control('title', [
          'id' => 'title',
          'class' => 'add_product_form_input text',
          'label' => false,
          'required' => false
        ]) ?>
      </td>
    </tr>
    <tr class="add_product_table_row">
      <th class="add_product_table_head">
        <label class="add_product_form_title" for="body">説明</label>
      </th>
      <td class="add_product_table_data pt32">
        <?= $this->Form->control('body', [
          'id' => 'body',
          'class' => 'add_product_form_input textarea',
          'label' => false,
          'required' => false
        ]) ?>
      </td>
    </tr>
    <tr class="add_product_table_row">
      <th class="add_product_table_head pt32">
        <label class="add_product_form_title" for="url_flg">ＵＲＬの有無</label>
      </th>
      <td class="add_product_table_data pt32">
        <?= $this->Form->radio('url_flg', [
          ['value' => 1, 'text' => 'あり'],
          ['value' => 0, 'text' => 'なし']
        ], [
          'value' => $post->url_flg ?? 1,
          'class' => 'add_product_form_input radio'
        ]) ?>
      </td>
    </tr>
    <tr id="url_input" class="add_product_table_row">
      <th class="add_product_table_head  pt32">
        <label class="add_product_form_title" for="url">ＵＲＬ</label>
      </th>
      <td class="add_product_table_data  pt32">
        <?= $this->Form->control('url', [
          'id' => 'url',
          'class' => 'add_product_form_input text',
          'label' => false,
          'required' => false
        ]) ?>
      </td>
    </tr>
    <tr class="add_product_table_row">
      <th class="add_product_table_head pt32">
        <label class="add_product_form_title" for="image_flg">画像の有無</label>
      </th>
      <td class="add_product_table_data pt32">
        <?= $this->Form->radio('image_flg', [
          ['value' => 1, 'text' => 'あり'],
          ['value' => 0, 'text' => 'なし']
        ], [
          'value' => $post->url_flg ?? 1,
          'class' => 'add_product_form_input radio'
        ]) ?>
      </td>
    </tr>
    <tr id="image_name_input" class="add_product_table_row">
      <th class="add_product_table_head  pt32">
        <label class="add_product_form_title" for="image_name">画像名</label>
      </th>
      <td class="add_product_table_data  pt32">
        <?= $this->Form->control('image_name', [
          'id' => 'image_name',
          'class' => 'add_product_form_input text',
          'label' => false,
          'required' => false
        ]) ?>
      </td>
    </tr>
    <tr id="image_path_input" class="add_product_table_row">
      <th class="add_product_table_head  pt32">
        <label class="add_product_form_title" for="image_path">画像</label>
      </th>
      <td class="add_product_table_data  pt32">
        <?= $this->Form->control('image_path', [
          'class' => 'dropify',
          'type' => 'file',
          'label' => false,
          'required' => false,
          'div' => true
        ]) ?>
      </td>
    </tr>
    <tr class="add_product_table_row">
      <th class="add_product_table_head  pt32"></th>
      <td class="add_product_table_data  pt32">
        <?= $this->Form->button('登録する', [
          'class' => 'btn btn_danger',
          'style' => 'width:100%;'
        ]) ?>
      </td>
    </tr>
  </table>
</div>
<?= $this->Form->end() ?>