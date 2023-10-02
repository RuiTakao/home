<?php

$this->Form->setTemplates([
  'inputContainer' => '{{content}}',
  'submitContainer' => '{{content}}'
]);
?>

<?php $this->start('css') ?>
<?= $this->Html->css('Admin/login') ?>
<?php $this->end() ?>

<div class="login_content card">
  <?= $this->Flash->render() ?>
  <h3 class="fz16 text_center">管理者ログイン</h3>
  <?= $this->Form->create() ?>
  <ul class="form_contents mt32">
    <li class="form_content">
      <label class="form_title fw600" for="username">ユーザー名</label>
      <div class="form_input">
        <?= $this->Form->control('username', [
          'type' => 'text',
          'id' => 'username',
          'class' => 'input',
          'label' => false,
          'required' => true
        ]) ?>
      </div>
    </li>
    <li class="form_content mt16">
      <label class="form_title fw600" for="password">パスワード</label>
      <div class="form_input">
        <?= $this->Form->control('password', [
          'type' => 'text',
          'id' => 'password',
          'class' => 'input',
          'label' => false,
          'required' => true
        ]) ?>
      </div>
    </li>
    <li class="form_content">
      <div></div>
      <?= $this->Form->submit('ログイン', [
        'class' => 'form_input login_btn btn btn_info mt16'
      ]); ?>
    </li>
  </ul>
  <?= $this->Form->end() ?>
</div>