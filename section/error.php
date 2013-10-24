<?php if (!is_array($s['d'])) $s['d'] = array($s['d']) ?>
<?php if (count($s['d'])): ?>
<p class="error">
<span>Error<?= count($s['d']) == 1 ? '' : 's' ?>:</span>
<?php foreach ($s['d'] as $e): ?>
<?= $e; ?><br>
<?php endforeach ?>
</p>
<?php endif ?>
