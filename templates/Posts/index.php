<ul class="lists">
  <?php foreach ($posts as $post) : ?>
    <li class="list">
      <div class="list_image">
        <?= $this->Html->image(h($post->image_path), ['class' => 'img', 'alt' => 'CakePHP']) ?>
      </div>
      <div class="list_text">
        <p class="list_title"><?= h($post->title) ?></p>
        <p class="list_body"><?= h($post->title) ?></p>
        <?= $this->Html->link('アプリを見る', h($post->url), ['class' => 'list_link', 'target' => '_blank']) ?>
      </div>
    </li>
  <?php endforeach; ?>
  </li>
</ul>