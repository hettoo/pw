<?php

$s['head'] = 'Admin config list';
$s['description'] = 'Admin config list.';

import_lib('common/admin');
if (is_null($s['admin']))
    return;

import_lib('Table');
import_lib('Search');
import_lib('Pager');

$s['suburl'] = array('order', 'page', 'search');

$table = new Table();
$table->addColumn(array('name' => 'key', 'title' => 'Key', 'size' => 'large'));
$table->addColumn(array('name' => 'value', 'title' => 'Value', 'size' => 'large'));
$table->addColumn(array('name' => 'actions', 'title' => '', 'size' => 'medium', 'no-order' => true));

$table->processOrder('key');

$search = new Search();
$like = $search->get();

$pager = new Pager();

$search->redirect($pager);

$pager->query('`key`, `value`', 'config', "WHERE `key` LIKE '%$like%'" . $table->getOrder());
$rows = $pager->getRows();
foreach ($rows as $row) {
    $table->addField($row['key']);
    $table->addField($row['value']);
    $table->addField('<a href="' . url('edit/' . $row['key'], 2) . '">edit</a> <a href="' . url('delete/' . $row['key'], 2) . '">delete</a>');
}
$table->setPager($pager);
$table->setSearch($search);

section('single', '<a href="' . url('edit', 2) . '">add</a>');
section('single', $table->format());

?>
