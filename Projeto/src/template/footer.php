
<?php if (!empty($template['scripts'])): ?>
    <?php foreach ($template['scripts'] as $src): ?>
        <script src="<?= $src ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>