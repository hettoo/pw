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

import_lib('init');

?>
