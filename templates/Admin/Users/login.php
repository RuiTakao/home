<?php

/** formテンプレート設定 */
$this->Form->setTemplates([
  'inputContainer' => '{{content}}',
  'submitContainer' => '{{content}}'
]);
?>

<?php /** css読み込み */ ?>
<?php $this->start('css') ?>
<?= $this->Html->css('Admin/login') ?>
<?php $this->end() ?>

<div class="login_form card">
  <?= $this->Flash->render() ?>
  <h3 class="fz16 text_center">管理者ログイン</h3>

  <?php /** form */ ?>
  <?= $this->Form->create() ?>
  <table class="mt16">

    <?php /** username */ ?>
    <tr>
      <th><label class="login_form_title" for="username">ユーザー名</label></th>
      <td>
        <?= $this->Form->control('username', [
          'type' => 'text',
          'id' => 'username',
          'class' => 'login_form_input pl4',
          'label' => false,
          'required' => true
        ]) ?>
      </td>
    </tr>

    <?php /** password */ ?>
    <tr>
      <th><label class="login_form_title" for="password">パスワード</label></th>
      <td>
        <?= $this->Form->control('password', [
          'type' => 'password',
          'id' => 'password',
          'class' => 'login_form_input pl4',
          'label' => false,
          'required' => true
        ]) ?>
      </td>
    </tr>

    <?php /** submit */ ?>
    <tr>
      <th></th>
      <td>
        <?= $this->Form->submit('ログイン', [
          'class' => 'login_form_btn btn info'
        ]); ?>
      </td>
    </tr>

  </table>
  <?= $this->Form->end() ?>
</div>