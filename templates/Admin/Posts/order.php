<?php $this->start('css') ?>
<?= $this->Html->css('Admin/Posts/index') ?>
<style>
    .card {
        border-radius: 20px;
        height: 500px;
    }

    .left {
        width: 75%;
    }

    .area {
        background: #ddd;
        border-radius: 16px;
        height: 100%;
        width: 100%;
        box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, 0.3);
    }

    .ul {
        padding: 8px;
    }

    .li {
        background: #fff;
        width: 100%;
        border-radius: 4px;
        height: 64px;
        padding: 4px 8px;
        font-size: 16px;
    }

    .li:not(:first-child) {
        margin-top: 8px;
    }
</style>
<?php $this->end() ?>

<div class="flex card">
    <div class="left">
        <div class="area">
            <ul class="ul">
                <?php foreach ($posts as $post) : ?>
                    <li class="li">
                        <p><?= $post->title ?></p>
                        <?= $this->Form->radio('status' . $post->id, [
                            ['value' => 1, 'text' => '表示'],
                            ['value' => 0, 'text' => '非表示'],
                        ], [
                            'value' => $post->status
                        ]) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="right">
        <button>この設定にする</button>
    </div>
</div>