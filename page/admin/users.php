<?php

$s['head'] = 'Admin user list';
$s['description'] = 'Admin user list.';

import_lib('common/admin');
if (!$s['admin_result'])
    return;

import_lib('Table');
import_lib('Search');
import_lib('Pager');

$s['suburl'] = array('order', 'page', 'search');

$table = new Table();
$table->addColumn(array('name' => 'name', 'title' => 'Name', 'size' => 'large'));
$table->addColumn(array('name' => 'password', 'title' => 'Password', 'size' => 'large'));
$table->addColumn(array('name' => 'email', 'title' => 'E-mail', 'size' => 'large'));

$table->processOrder('name');

$search = new Search();
$like = $search->get();

$pager = new Pager();

$search->redirect($pager);

$pager->query('`name`, `password`, `email`', 'admin', "WHERE `name` LIKE '%$like%'" . $table->getOrder());
$rows = $pager->getRows();
foreach ($rows as $row) {
    $table->addField($row['name']);
    $table->addField($row['password']);
    $table->addField($row['email']);
}
$table->setPager($pager);
$table->setSearch($search);

section('single', $table->format());

?>
