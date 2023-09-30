<!DOCTYPE html>
<html>

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>たかおの作品リスト</title>
    <?= $this->Html->meta('icon') ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">

    <?= $this->Html->css(['home']) ?>
</head>

<body>
    <header class="header">
        <div class="container">
            <h1 class="header_title">たかおの作品リスト</h1>
        </div>
    </header>
    <main class="main">
        <div class="container">
            <?= $this->fetch('content') ?>
        </div>
    </main>
</body>

</html>