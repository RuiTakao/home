<ul class="lists">
    <?php foreach ($posts as $post) : ?>
        <li class="list">
            <div class="list_image">
                <?= $this->Html->image(h($post->image_path), ['class' => 'img', 'alt' => 'CakePHP']) ?>
            </div>
            <div class="list_text">
                <p class="list_title"><?= h($post->title) ?></p>
                <p class="list_title">画像名： <?= h($post->title) ?></p>
                <p>URL: <?= $this->Html->link(h($post->url), h($post->url), ['target' => '_blank']) ?></p>
                <p class="list_body"><?= h($post->body) ?></p>
                <?= $this->Html->link('編集する', ['controller' => 'posts', 'action' => 'edit', $post->id], ['class' => 'list_link']) ?>
            </div>
        </li>
    <?php endforeach; ?>
    </li>
</ul>