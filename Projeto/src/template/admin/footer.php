        </div>
    </div>
</div>


<script src="static/js/main.js"></script>
<script src="static/lib/bootstrap-5.3.3/js/bootstrap.min.js"></script>

<?php if (!empty($template['scripts'])): ?>
    <?php foreach ($template['scripts'] as $src): ?>
        <script src="<?= $src ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>