<?php

import_lib('TableSetup');

$setup = new TableSetup(prefix('config'));
$setup->add('key', 'VARCHAR(32) NOT NULL PRIMARY KEY');
$setup->add('value', 'TEXT');
$setup->setup();

$setup = new TableSetup(prefix('page'));
$setup->add('id', 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY');
$setup->add('page', 'TEXT');
$setup->add('head', 'TEXT');
$setup->add('title', 'TEXT');
$setup->setup();

$setup = new TableSetup(prefix('content'));
$setup->add('id', 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY');
$setup->add('page', 'INT NOT NULL');
$setup->add('content', 'TEXT');
$setup->add('ranking', 'INT DEFAULT 0 NOT NULL');
$setup->setup();

$setup = new TableSetup(prefix('page_images'));
$setup->add('id', 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY');
$setup->add('page', 'INT');
$setup->add('file', 'VARCHAR(64)');
$setup->add('description', 'TEXT');
$setup->setup();

$setup = new TableSetup(prefix('admin'));
$setup->add('id', 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY');
$setup->add('level', 'INT DEFAULT 0 NOT NULL');
$setup->add('name', 'VARCHAR(64) NOT NULL');
$setup->add('password', 'VARCHAR(64) NOT NULL');
$setup->add('email', 'VARCHAR(64) NOT NULL');
$setup->setup();

?>
