<?php

/** formテンプレート設定 */
$this->Form->setTemplates([
  'inputContainer' => '{{content}}',
  'submitContainer' => '{{content}}'
]);
?>
<!DOCTYPE html>
<html>

<head>
  <?= $this->Html->charset() ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>たかおの作品リスト</title>
  <?= $this->Html->meta('icon') ?>
  <?= $this->fetch('meta') ?>

  <!-- css -->
  <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">
  <?= $this->Html->css(['reset']) ?>
  <?= $this->Html->css(['utility']) ?>
  <?= $this->Html->css(['Admin/header']) ?>
  <?= $this->Html->css(['Admin/login']) ?>
</head>

<body>
  <header class="header">
    <div class="header_container container flex justify-space-between align-center">
      <h1 class="header_title">takaoFollio 管理画面</h1>
      <?php if (!is_null($this->request->getAttribute('identity'))) : ?>
        <?= $this->Html->link('ログアウト', ['controller' => 'users', 'action' => 'logout'], ['class' => 'logout flex justify-center align-center']) ?>
      <?php endif; ?>
    </div>
  </header>
  <main class="main">
    <div class="main_container">
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
    </div>
  </main>
</body>

</html>