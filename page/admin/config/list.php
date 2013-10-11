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
$table->addColumn(array('title' => 'Key', 'size' => 'large'));
$table->addColumn(array('title' => 'Value', 'size' => 'large'));
$table->addColumn(array('name' => 'actions', 'title' => '', 'size' => 'medium', 'no-order' => true));
$table->processOrder('key');

$pager = $table->setPager();
$search = $table->setSearch();

$like = $search->getLike();
$order = $table->getOrder();

$pager->query('*', 'config', "WHERE `key`$like OR `value`$like$order", function ($row, $args) {
    list($table, $action_index) = $args;
    $table->addField($row['key']);
    $table->addField($row['value']);
    $table->addField(action_list($action_index, array(array('edit/' . $row['key'], 'edit'), array('delete/' . $row['key'], 'delete')), ' '));
}, array($table, $action_index));

section('clean', $urls);
section('table', $table);
section('clean', $urls);

?>
