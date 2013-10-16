<p class="error">
<?php if (is_array($s['d'])): ?>
<span>Errors:</span>
<?php foreach ($s['d'] as $e): ?>
<?= $e; ?><br />
<?php endforeach ?>
<?php else: ?>
<?= $s['d']; ?>
<?php endif ?>
</p>
