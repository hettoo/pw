<?php

function pw_file($file) {
    if (file_exists('custom/' . $file))
        return 'custom/' . $file;
    elseif (file_exists($file))
        return $file;
    else
        return null;
}

function script($script) {
    return pw_file($script . '.php');
}

function import_once($script) {
    global $base, $args, $hierarchy;
    global $s;
    include_once(script($script));
}

function import($script) {
    global $base, $args, $hierarchy;
    global $s;
    include(script($script));
}

function import_raw($file) {
    include(pw_file($file));
}

function import_lib($lib) {
    import_once("lib/$lib");
}

function resource_url($target) {
    global $base;
    return $base . (substr($base, -1) == '/' ? '' : '/') . 'r/' . $target;
}

function url($target, $level = 0, $rootify = true) {
    global $base, $args, $hierarchy;
    $result = $base . $args[0];
    for ($i = 0; $i < count($hierarchy); $i++) {
        if ($i > 0)
            $result .= '/';
        if ($i == $level) {
            $result .= $target;
            if ($rootify)
                return $result;
        } else {
            $result .= $hierarchy[$i];
        }
    }
    if ($i <= $level) {
        if ($i > 0)
            $result .= '/';
        $result .= $target;
    }
    return $result;
}

function this_url() {
    global $hierarchy;
    return url(join('/', $hierarchy));
}

function read_line($fp) {
    $result = trim(fgets($fp));
    return $result;
}

function split_values($string) {
    $values = explode(',', $string);
    $result = array();
    foreach ($values as $value) {
        if (!empty($value))
            $result[] = $value;
    }
    return $result;
}

$base = substr($_SERVER['SCRIPT_NAME'], 0, -10);
$args = preg_replace('/\/$/', '', substr($_SERVER['REQUEST_URI'], strlen($base)));
if (empty($args))
    $args = '/';

$s = array();
$hierarchy = explode('/', substr($args, 1));

$s['project'] = 'Personal Website';
$s['analytics'] = '';

$s['menu'] = array(
    array('', 'Home'),
    'Test'
);
$s['css'] = 'main';
$s['max_pages'] = 8;

if (file_exists('setup')) {
    $fp = fopen('setup', 'r');
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
