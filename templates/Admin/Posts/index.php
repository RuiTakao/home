<ul class="lists">
    <?php foreach ($posts as $post) : ?>
        <li class="list list_add">
            <div class="list_image list_add_image">
                <?= $this->Html->image(h($post->image_path), ['class' => 'img', 'alt' => 'CakePHP']) ?>
            </div>
            <div class="list_text">
                <p class="list_title"><?= h($post->title) ?><span class="list_add_status status_true">表示</span></p>
                <p class="list_sub_title mt8"><span class="weight">画像名：</span> <?= h($post->title) ?></p>
                <p class="list_sub_title"><span class="weight">URL：</span> <?= $this->Html->link(h($post->url), h($post->url), ['target' => '_blank']) ?></p>
                <p class="list_body"><?= h($post->body) ?></p>
                <?= $this->Html->link('編集する', ['controller' => 'posts', 'action' => 'edit', $post->id], ['class' => 'list_link list_add_link']) ?>
            </div>
        </li>
    <?php endforeach; ?>
    </li>
</ul>