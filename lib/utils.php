<?php

function resource_url($target) {
    global $base;
    return $base . ($base[-1] == '/' ? '' : '/') . 'r/' . $target;
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

?>
