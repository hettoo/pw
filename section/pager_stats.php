<?php

list($pager, $type) = $s['d'];

?>
<?php if ($pager->getTotal()): ?>
<p>
Displaying <?= $pager->getDisplayed() ?> / <?= $pager->getTotal() ?> <?= $type ?>.
</p>
<?php endif ?>
