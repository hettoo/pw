<?php

$pager = $s['d'];
if (!$pager->drawable())
    return;
$index = $pager->getIndex();
$page = $pager->getPage() + 1;
list($start, $end) = $pager->getRange();
$pages = $pager->getPages();

?>
<ul class="pager">
    <li><a href="<?= url(1, $index, false) ?>">&lt;&lt;</a></li>
    <li><a href="<?= url(max($page - 1, 1), $index, false) ?>">&lt;</a></li>
    <?php if ($start > 1): ?>
        ...
    <?php endif ?>
    <?php for ($i = $start; $i <= $end; $i++): ?>
        <li<?= ($i == $page ? ' class="active"' : '') ?>><a href="<?= url($i, $index, false) ?>"><?= $i ?></a></li>
    <?php endfor ?>
    <?php if ($end < $pages): ?>
        ...
    <?php endif ?>
    <li><a href="<?= url(min($page + 1, $pages), $index, false) ?>">&gt;</a></li>
    <li><a href="<?= url($pages, $index, false) ?>">&gt;&gt;</a></li>
</ul>
