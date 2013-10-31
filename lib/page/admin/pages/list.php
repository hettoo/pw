<?php

$s['head'] = 'Admin page list';
$s['description'] = 'Admin page list.';

import_lib('common/admin');
if (is_null($s['admin']))
    return;

import_lib('interactive/Table');

$s['suburl'] = array('order', 'page', 'search');
$action_index = page_index();

$table = new Table();
$table->addColumn(array('title' => 'Id', 'size' => 'small'));
$table->addColumn(array('title' => 'Title', 'size' => 'large'));
$table->addColumn(array('title' => 'URL', 'column' => 'page', 'size' => 'large'));
$table->addColumn(array('name' => 'actions', 'title' => '', 'no-order' => true));
$table->processOrder('id');

$pager = $table->setPager();
$search = $table->setSearch();

$like = $search->getLike();
$order = $table->getOrder();

$pager->query('*', prefix('page') . " WHERE `page`$like OR `title`$like$order", function ($row, $args) {
    global $s;
    list($table, $action_index) = $args;
    $table->addField($row['id']);
    $table->addField(secure($row['title'], 'html'));
    $table->addField(secure($row['page'], 'html'));
    $table->addField(admin_actions($action_index, $row['id'], true));
}, array($table, $action_index));

$urls = admin_actions($action_index);
admin_upper_urls($urls);
section('table', $table);
admin_lower_urls($urls);

?>
