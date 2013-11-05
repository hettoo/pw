<?php

list($pager, $type) = $s['d'];

?>
<p>
Displaying <?= $pager->getDisplayed() ?> / <?= $pager->getTotal() ?> <?= $type ?>.
</p>
