<?php

import_lib('utils/main');

$s = array();
$s['base'] = substr($_SERVER['SCRIPT_NAME'], 0, -9);
$s['args'] = preg_replace('/\/$/', '', substr($_SERVER['REQUEST_URI'], strlen($s['base'])));
$s['h'] = explode('/', $s['args']);

import_lib('core/init_config');
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
    $s['args'] = 'pw';
}

if (!isset($s['libs']))
    $s['libs'] = 'core/content';
$libs = split_values($s['libs']);
foreach ($libs as $lib)
    import_lib($lib);

?>
