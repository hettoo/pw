<?php

$table = $s['d'];
$content = $table->getContent();
$search = $table->getSearch();
$pager = $table->getPager();
$head = $table->getHead();
if (empty($content)) {
    if (isset($search))
        section('search', $search);
    section('single', $table->getEmptyMessage());
    return;
}
if (isset($search))
    section('search', $search);
else if (isset($pager))
    section('pager', $pager);

if (isset($pager)) {
    $index = $pager->getIndex();
    $page = $hierarchy[$index];
    $hierarchy[$index] = '1';
}

?>

<table<?= $head ? '' : ' class="headless"' ?>>
<?php if ($head): ?>
    <tr>
    <?php foreach ($table->getColumns() as $values): ?>
        <th>
        <div<?= format_classes($table->getClasses($values)) ?>>
        <?php if ($table->canOrder($values)): ?>
            <a href="<?= url($table->invert($values['name']), $table->getOrderIndex(), false) ?>"><?= $values['title'] . $table->suffix($values['name']) ?></a>
        <?php else: ?>
            <?= $values['title'] ?>
        <?php endif ?>
        </div>
        </th>
    <?php endforeach ?>
    </tr>
<?php endif ?>
<?= $table->getContent() ?>
</table>

<?php

if (isset($pager))
    $hierarchy[$pager->getIndex()] = $page;

?>
