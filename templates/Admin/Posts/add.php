<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 */
$this->Form->setTemplates([
    'inputContainer' => '{{content}}'
])
?>
<div class="lists">
    <div class="list list_add" style="display:block;">
        <?= $this->Form->create($post, [
            'type' => 'file'
        ]) ?>
        <ul class="form_lists">
            <li class="form_list">
                <div class="form_list_title">
                    <label for="title">作品名：</label>
                </div>
                <div class="form_list_input">
                    <?= $this->Form->control('title', [
                        'id' => 'title',
                        'label' => false,
                        'required' => false
                    ]) ?>
                </div>
            </li>
            <li class="form_list">
                <div class="form_list_title">
                    <label for="body">説明：</label>
                </div>
                <div class="form_list_input">
                    <?= $this->Form->control('body', [
                        'id' => 'body',
                        'type' => 'textarea',
                        'label' => false,
                        'required' => false
                    ]) ?>
                </div>
            </li>
            <li class="form_list">
                <div class="form_list_title">
                    <label for="status">ステータス：</label>
                </div>
                <div class="form_list_input">
                    <?= $this->Form->radio('status', [
                        ['value' => 1, 'text' => '表示'],
                        ['value' => 0, 'text' => '非表示']
                    ]) ?>
                </div>
            </li>
            <li class="form_list">
                <div class="form_list_title">
                    <label for="url">URL：</label>
                </div>
                <div class="form_list_input">
                    <?= $this->Form->control('url', [
                        'id' => 'url',
                        'label' => false,
                        'required' => false
                    ]) ?>
                    <?= $this->Form->checkbox('url_flg', [
                        'id' => 'url_flg'
                    ]) ?><label for="url_flg">なし</label>
                </div>
            </li>
            <li class="form_list">
                <div class="form_list_title">
                    <label for="url">画像名：</label>
                </div>
                <div class="form_list_input">
                    <?= $this->Form->control('image_name', [
                        'id' => 'image_name',
                        'label' => false,
                        'required' => false
                    ]) ?>
                    <?= $this->Form->checkbox('image_flg', [
                        'id' => 'image_flg'
                    ]) ?><label for="image_flg">なし</label>
                    <?= $this->Form->control('image_path', [
                        'type' => 'file',
                        'label' => false,
                        'required' => false
                    ]) ?>
                </div>
            </li>
        </ul>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>
</div>