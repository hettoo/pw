<?php

$s['head'] = 'Admin user list';
$s['description'] = 'Admin user list.';

import_lib('common/admin');
if (is_null($s['admin']))
    return;

import_lib('Table');

$s['suburl'] = array('order', 'page', 'search');
$action_index = page_index();
$urls = action_list($action_index, array(array('edit', 'add')));

$table = new Table();
$table->addColumn(array('title' => 'Id', 'size' => 'small'));
$table->addColumn(array('title' => 'Name', 'size' => 'large'));
if ($s['admin']->permits($s['admin_level']))
    $table->addColumn(array('title' => 'Password', 'size' => 'large'));
$table->addColumn(array('title' => 'Level', 'size' => 'small'));
$table->addColumn(array('name' => 'email', 'title' => 'E-mail', 'size' => 'large'));
$table->addColumn(array('name' => 'actions', 'title' => '', 'size' => 'medium', 'no-order' => true));
$table->processOrder('id');

$pager = $table->setPager();
$search = $table->setSearch();

$like = $search->getLike();
$order = $table->getOrder();

$pager->query('*', 'admin', "WHERE `name`$like$order", function ($row, $args) {
    global $s;
    list($table, $action_index) = $args;
    $table->addField($row['id']);
    $table->addField($row['name']);
    if ($s['admin']->permits($s['admin_level']))
        $table->addField($row['password']);
    $table->addField($row['level']);
    $table->addField($row['email']);
    $table->addField(action_list($action_index, array(array('edit/' . $row['id'], 'edit'), array('delete/' . $row['id'], 'delete')), ' '));
}, array($table, $action_index));

section('clean', $urls);
section('table', $table);
section('clean', $urls);

?>
