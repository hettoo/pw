<?php

function create_menu($level, $menu) {
    global $s;
    $result = '';
    foreach ($menu as $item) {
        if (is_array($item)) {
            list($link, $name) = $item;
        } else {
            $link = nicen($item);
            $name = $item;
        }
        $result .= '<div';
        if ($s['h'][$level] == $link)
            $result .= ' class="active"';
        $external = preg_match('/^\/\//', $link);
        $result .= '><a' . ($external ? ' class="external"' : ''). ' href="' . ($external ? 'http:' . $link : url($link, $level)) . '"' . ($external ? ' target="_blank"' : '') . '>' . $name . '</a></div>';
    }
    return $result;
}

function action_list($level, $list, $glue = ' | ') {
    $result = '<div class="actions">';
    $first = true;
    foreach ($list as $item) {
        if ($first)
            $first = false;
        else
            $result .= $glue;
        if (is_array($item)) {
            list($link, $name) = $item;
        } else {
            $link = nicen($item);
            $name = $item;
        }
        $result .= '<a href="' . url($link, $level) . '">' . $name . '</a>';
    }
    $result .= '</div>';
    return $result;
}

function format_rank($rank) {
    return ($rank + 1) . '.';
}

function create_element($name, $content = '', $attributes = array()) {
    $result = '<' . $name;
    foreach ($attributes as $key => $value)
        $result .= ' ' . $key . '="' . secure($value, 'html') . '"';
    if (autoclose($name))
        $result .= ' /';
    $result .= '>';
    if (!autoclose($name)) {
        $result .= $content . secure($attributes['value'], 'html');
        $result .= '</' . $name . '>';
    }
    return $result;
}

function format_classes($classes) {
    if (!isset($classes) || empty($classes))
        return '';
    return ' class="' . implode(' ', $classes) . '"';
}

?>
