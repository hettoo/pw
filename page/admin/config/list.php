<?php

$s['head'] = 'Admin config list';
$s['description'] = 'Admin config list.';

import_lib('common/admin');
if (is_null($s['admin']))
    return;

import_lib('Table');

$s['suburl'] = array('order', 'page', 'search');
$action_index = page_index();
$urls = action_list($action_index, array(array('edit', 'add')));

$table = new Table();
$table->addColumn(array('name' => 'key', 'title' => 'Key', 'size' => 'large'));
$table->addColumn(array('name' => 'value', 'title' => 'Value', 'size' => 'large'));
$table->addColumn(array('name' => 'actions', 'title' => '', 'size' => 'medium', 'no-order' => true));
$table->processOrder('key');

$pager = $table->setPager();
$search = $table->setSearch();

$like = $search->getLike();
$order = $table->getOrder();

$pager->query('`key`, `value`', 'config', "WHERE `key`$like OR `value`$like$order", function ($row, $args) {
    list($table, $action_index) = $args;
    $table->addField($row['key']);
    $table->addField($row['value']);
    $table->addField('<a href="' . url('edit/' . $row['key'], $action_index) . '">edit</a> <a href="' . url('delete/' . $row['key'], $action_index) . '">delete</a>');
}, array($table, $action_index));

section('single', $urls);
section('single', $table->format());
section('single', $urls);

?>
