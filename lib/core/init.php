<?php

import_lib('utils/main');

$s['base'] = substr($_SERVER['SCRIPT_NAME'], 0, -9);
$s['h'] = explode('/', preg_replace('/\/$/', '', substr($_SERVER['REQUEST_URI'], strlen($s['base']))));
$base = preg_replace('/\/+$/', '/', $s['base']);
if ($base != $s['base']) {
    $s['base'] = $base;
    redirect(url(implode($s['h'])));
}

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
    $s['h'] = array('pw');
}

if (!isset($s['libs']))
    $s['libs'] = 'core/content';
$libs = split_values($s['libs']);
foreach ($libs as $lib)
    import_lib($lib);

?>
