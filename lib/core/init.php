<?php

import_lib('utils/main');

$base = substr($_SERVER['SCRIPT_NAME'], 0, -9);
$args = preg_replace('/\/$/', '', substr($_SERVER['REQUEST_URI'], strlen($base)));

$hierarchy = explode('/', $args);

$s = array();

$s['project'] = 'Personal Website';
$s['analytics'] = '';

$s['charset'] = 'iso-8859-1';
$s['menu'] = ':Home';
$s['css'] = 'main';
$s['max_pages'] = 8;

$s['header'] = '';
$s['suburl'] = array();

$s['prefix'] = 'pw_';

import_lib('core/database');

if (file_exists('setup/setup')) {
    $fp = fopen('setup/setup', 'r');
    $s['host'] = read_line($fp);
    $s['database'] = read_line($fp);
    $s['user'] = read_line($fp);
    $s['password'] = read_line($fp);
    $s['prefix'] = read_line($fp);
    fclose($fp);

    load_db();
} else {
    $args = 'pw';
}

if (!isset($s['libs']))
    $s['libs'] = 'core/content';
$libs = split_values($s['libs']);
foreach ($libs as $lib)
    import_lib($lib);

?>
