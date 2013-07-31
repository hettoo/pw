<?php

function resource_url($target) {
    global $base;
    return $base . 'r/' . $target;
}

function url($target, $level = 0, $rootify = true) {
    global $base, $hierarchy;
    $result = $base;
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

function write_line($fp, $line) {
    fwrite($fp, $line);
    fwrite($fp, "\n");
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

function autoclose($name) {
    return $name == 'br' || $name == 'img' || $name == 'input';
}

function parse_menu($string) {
    $result = array();
    $pairs = explode(',', $string);
    foreach ($pairs as $pair) {
        if (strpos($pair, ':') === false)
            $result[] = $pair;
        else
            $result[] = explode(':', $pair);
    }
    return $result;
}

?>
