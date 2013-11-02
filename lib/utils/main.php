<?php

function import($script, $original = false) {
    global $s;
    $script = script($script, $original);
    return include($script);
}

function import_raw($file, $original = false) {
    $file = pw_file($file, $original);
    return include($file);
}

function resource_url($target) {
    global $s;
    return $s['base'] . 'r/' . $target;
}

function resource($target) {
    return 'r/' . $target;
}

function theme_url($target) {
    global $s;
    return resource_url('themes/' . $s['theme'] . '/' . $target);
}

function theme_resource($target) {
    global $s;
    return resource('themes/' . $s['theme'] . '/' . $target);
}

function url($target, $level = 0, $rootify = true) {
    global $s;
    $result = $s['base'];
    for ($i = 0; $i < count($s['h']); $i++) {
        if ($i > 0)
            $result .= '/';
        if ($i == $level) {
            $result .= $target;
            if ($rootify)
                return $result;
        } else {
            $result .= $s['h'][$i];
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
    global $s;
    return url(implode('/', $s['h']));
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

function parse_hash($string) {
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
    global $s;
    $index = find_index($string);
    if (isset($index))
        return $s['h'][$index];
    return null;
}

function nicen($string) {
    return preg_replace('/[^a-z0-9_\-]/', '', strtolower($string));
}

function redirect_current() {
    global $s;
    redirect(url(implode('/', $s['h'])));
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
    $string = str_replace('%', '%25', $string);
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

function action_page($callback) {
    global $s;
    $s['suburl'] = array('id');
    $id = find_value('id');
    if (isset($id))
        $callback($id);
    redirect_up();
}

?>
