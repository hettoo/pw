<?php

$pager = $s['d'];
if (!$pager->drawable())
    return;
$index = $pager->getIndex();
$page = $pager->getPage() + 1;
list($start, $end) = $pager->getRange();
$pages = $pager->getPages();

?>
<div class="pager">
    <span><a href="<?= url(1, $index, false) ?>">&lt;&lt;</a></span>
    <span><a href="<?= url(max($page - 1, 1), $index, false) ?>">&lt;</a></span>
    <?php if ($start > 1): ?>
        ...
    <?php endif ?>
    <?php for ($i = $start; $i <= $end; $i++): ?>
        <span<?= ($i == $page ? ' class="active"' : '') ?>><a href="<?= url($i, $index, false) ?>"><?= $i ?></a></span>
    <?php endfor ?>
    <?php if ($end < $pages): ?>
        ...
    <?php endif ?>
    <span><a href="<?= url(min($page + 1, $pages), $index, false) ?>">&gt;</a></span>
    <span><a href="<?= url($pages, $index, false) ?>">&gt;&gt;</a></span>
</div>
