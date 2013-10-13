<?php

$s['head'] = 'Admin page list';
$s['description'] = 'Admin page list.';

import_lib('common/admin');
if (is_null($s['admin']))
    return;

import_lib('Table');

$s['suburl'] = array('order', 'page', 'search');
$action_index = page_index();

$table = new Table();
$table->addColumn(array('title' => 'Id', 'size' => 'small'));
$table->addColumn(array('title' => 'Page', 'size' => 'large'));
$table->addColumn(array('name' => 'actions', 'title' => '', 'size' => 'medium', 'no-order' => true));
$table->processOrder('id');

$pager = $table->setPager();
$search = $table->setSearch();

$like = $search->getLike();
$order = $table->getOrder();

$pager->query('*', 'page', "WHERE `page`$like$order", function ($row, $args) {
    global $s;
    list($table, $action_index) = $args;
    $table->addField($row['id']);
    $table->addField($row['page']);
    $table->addField(action_list($action_index, array(array('edit/' . $row['id'], 'edit'), array('fotos/' . $row['id'], 'fotos'), array('delete/' . $row['id'], 'delete')), ' '));
}, array($table, $action_index));

$urls = admin_actions($action_index);
section('clean', $urls);
section('table', $table);
section('clean', $urls);

?>
