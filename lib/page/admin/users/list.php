<?php

$s['head'] = 'Admin user list';
$s['description'] = 'Admin user list.';

import_lib('common/admin');
if (is_null($s['admin']))
    return;

import_lib('interactive/Table');

$s['suburl'] = array('order', 'page', 'search');
$action_index = page_index();

$table = new Table();
$table->addColumn(array('title' => 'Id', 'size' => 'small'));
$table->addColumn(array('title' => 'Name', 'size' => 'medium'));
if ($s['admin']->permits($s['admin_level']))
    $table->addColumn(array('title' => 'Password', 'size' => 'huge'));
$table->addColumn(array('title' => 'Level', 'size' => 'small'));
$table->addColumn(array('name' => 'email', 'title' => 'E-mail', 'size' => 'large'));
$table->addColumn(array('name' => 'actions', 'title' => '', 'no-order' => true));
$table->processOrder('id');

$pager = $table->setPager();
$search = $table->setSearch();

$like = $search->getLike();
$order = $table->getOrder();

$pager->query('*', prefix('admin') . " WHERE `name`$like$order", function ($row, $args) {
    global $s;
    list($table, $action_index) = $args;
    $table->addField($row['id']);
    $table->addField(secure($row['name'], 'html'));
    if ($s['admin']->permits($s['admin_level']))
        $table->addField($row['password']);
    $table->addField($row['level']);
    $table->addField(secure($row['email'], 'html'));
    $table->addField(admin_actions($action_index, $row['id'], true));
}, array($table, $action_index));

$urls = admin_actions($action_index);
admin_upper_urls($urls);
$table->show();
admin_lower_urls($urls);

?>
