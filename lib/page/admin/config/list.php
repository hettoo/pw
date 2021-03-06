<?php

$s['head'] = 'Admin config list';
$s['description'] = 'Admin config list.';

import_lib('common/admin');
if (is_null($s['admin']))
    return;

import_lib('interactive/Table');

$s['suburl'] = array('order', 'page', 'search');
$action_index = page_index();

$table = new Table();
$table->addColumn(array('title' => 'Key', 'size' => 'large'));
$table->addColumn(array('title' => 'Value', 'size' => 'large'));
$table->addColumn(array('name' => 'actions', 'title' => '', 'no-order' => true));
$table->processOrder('key');

$pager = $table->setPager();
$search = $table->setSearch();

$like = $search->getLike();
$order = $table->getOrder();

$pager->query('*', prefix('config') . " WHERE `key`$like OR `value`$like$order", function ($row, $args) {
    list($table, $action_index) = $args;
    $table->addField(secure($row['key'], 'html'));
    $table->addField(secure($row['value'], 'html'));
    $table->addField(admin_actions($action_index, $row['key'], true));
}, array($table, $action_index));

$urls = admin_actions($action_index);
admin_upper_urls($urls);
$table->show();
admin_lower_urls($urls);

?>
