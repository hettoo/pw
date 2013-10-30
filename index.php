<?php

function pw_file($file, $original = false) {
    if (!$original && file_exists('custom/' . $file))
        return 'custom/' . $file;
    elseif (file_exists($file))
        return $file;
    else
        return null;
}

function script($script, $original = false) {
    return pw_file($script . '.php', $original);
}

function import_once($script, $original = false) {
    global $s;
    $script = script($script, $original);
    return include_once($script);
}

function lib_file($lib) {
    return 'lib/' . $lib;
}

function inherit() {
    global $s;
    $lib = end($s['l']);
    reset($s['l']);
    return import_once($lib, true);
}

function import_lib($lib) {
    global $s;
    $lib = lib_file($lib);
    $s['l'][] = $lib;
    $result = import_once($lib);
    array_pop($s['l']);
    return $result;
}

function redirect_raw($location) {
    header('Location: ' . $location);
    exit;
}

function redirect($url) {
    redirect_raw('http://' . $_SERVER['HTTP_HOST'] . $url);
}

$s = array('l' => array());
import_lib('core/init');

?>
