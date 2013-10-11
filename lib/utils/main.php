<?php

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

function resource_url($target) {
    global $base;
    return $base . 'r/' . $target;
}

function resource($target) {
    return 'r/' . $target;
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

function simplify($string) {
    $result = strtolower($string);
    $result = preg_replace('/[^a-zA-Z0-9\s]+/', '', $result);
    $result = preg_replace('/\s+/', ' ', $result);
    return $result;
}

function page_index() {
    global $s;
    return substr_count($s['page'], '/');
}

function find_index($string) {
    global $s;
    $count = count($s['suburl']);
    for ($i = 0; $i < $count; $i++) {
        if ($s['suburl'][$i] == $string)
            return page_index() + $i + 1;
    }
    return null;
}

function find_value($string) {
    global $hierarchy;
    $index = find_index($string);
    if (isset($index))
        return $hierarchy[$index];
    return null;
}

function nicen($string) {
    return preg_replace('/[^a-z0-9_\-]/', '', strtolower($string));
}

function redirect_raw($location) {
    header('Location: ' . $location);
    exit;
}

function redirect($url) {
    redirect_raw('http://' . $_SERVER['HTTP_HOST'] . $url);
}

function redirect_up($levels = 1) {
    global $s;
    $url = explode('/', $s['page']);
    for ($i = 0; $i < $levels; $i++)
        array_pop($url);
    redirect(url(implode('/', $url)));
}

function redirect_back() {
    redirect_raw($_SERVER['HTTP_REFERER']);
}

function escape_url($string) {
    $string = str_replace('|', '||', $string);
    $string = str_replace('\\', '|]', $string);
    $string = str_replace('/', '|[', $string);
    $string = str_replace('#', '|+', $string);
    return $string;
}

function unescape_url($string) {
    $string = rawurldecode($string);
    $length = strlen($string);
    $result = '';
    $escaped = false;
    for ($i = 0; $i < $length; $i++) {
        if ($escaped) {
            switch ($string[$i]) {
            case '|':
                $result .= '|';
                break;
            case ']':
                $result .= '\\';
                break;
            case '[':
                $result .= '/';
                break;
            case '+':
                $result .= '#';
                break;
            }
            $escaped = false;
        } else {
            if ($string[$i] == '|')
                $escaped = true;
            else
                $result .= $string[$i];
        }
    }
    if ($escaped)
        $result .= '|';
    return $result;
}

function extension($file) {
    return pathinfo($file, PATHINFO_EXTENSION);
}

?>
