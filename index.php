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
    global $base, $args, $hierarchy;
    global $s;
    $script = script($script, $original);
    return include_once($script);
}

function import($script, $original = false) {
    global $base, $args, $hierarchy;
    global $s;
    $script = script($script, $original);
    return include($script);
}

function import_raw($file, $original = false) {
    $file = pw_file($file, $original);
    return include($file);
}

function import_lib($lib) {
    $lib = 'lib/' . $lib;
    if (!import_once($lib))
        return import_once($lib, true);
    return true;
}

import_lib('core/init');

?>
