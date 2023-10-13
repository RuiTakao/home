<?php if ($session->read('message')) : ?>
    <script>
        window.onload = () => alert('<?= $session->read('message') ?>');
    </script>
    <?php $session->delete('message') ?>
<?php endif; ?>