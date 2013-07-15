<?php

import_lib('utils');

$base = substr($_SERVER['SCRIPT_NAME'], 0, -10);
$args = preg_replace('/\/$/', '', substr($_SERVER['REQUEST_URI'], strlen($base)));
if (empty($args))
    $args = '/';

$hierarchy = explode('/', substr($args, 1));

$s = array();

$s['project'] = 'Personal Website';
$s['analytics'] = '';

$s['menu'] = array(
    array('', 'Home'),
    'Test'
);
$s['css'] = 'main';
$s['max_pages'] = 8;

if (file_exists('setup/setup')) {
    $fp = fopen('setup/setup', 'r');
    $s['host'] = read_line($fp);
    $s['database'] = read_line($fp);
    $s['user'] = read_line($fp);
    $s['password'] = read_line($fp);
    fclose($fp);

    import_lib('database');
} else {
    $args = '/pw';
}

if (!isset($s['libs']))
    $s['libs'] = 'content';
$libs = split_values($s['libs']);
foreach ($libs as $lib)
    import_lib($lib);

?>
