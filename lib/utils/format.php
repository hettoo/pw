<?php

function create_menu($level, $menu) {
    global $hierarchy;
    $result = '';
    foreach ($menu as $item) {
        if (is_array($item)) {
            list($link, $name) = $item;
        } else {
            $link = nicen($item);
            $name = $item;
        }
        $result .= '<div';
        if ($hierarchy[$level] == $link)
            $result .= ' class="active"';
        $result .= '><a href="' . url($link, $level) . '">' . $name . '</a></div>';
    }
    return $result;
}

function action_list($level, $list) {
    $result = '<div class="actions">';
    $first = true;
    foreach ($list as $item) {
        if ($first)
            $first = false;
        else
            $result .= ' | ';
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

function format_date_relative($time) {
    return '<span class="time">' . relative_time($time) . ($time == 0 ? '' : '<span class="exacttime">' . exact_datetime($time) . '</span>') . '</span>';
}

function format_date($time) {
    return '<span class="time">' . exact_date($time) . ($time == 0 ? '' : '<span class="exacttime">' . exact_time($time) . '</span>') . '</span>';
}

function format_time($time) {
    $negative = $time < 0;
    $time = abs($time);
    $result = '.' . sprintf('%03d', $time % 1000);
    $time = floor($time / 1000);
    $result = sprintf('%' . ($time >= 60 ? '02' : '') . 'd', $time % 60) . $result;
    $time = floor($time / 60);
    if ($time > 0) {
        $result = sprintf('%' . ($time >= 60 ? '02' : '') . 'd', $time % 60) . ':' . $result;
        $time = floor($time / 60);
        if ($time > 0)
            $result = $time . ':' . $result;
    }
    if ($negative)
        $result = '-' . $result;
    return $result;
}

function format_rank($rank) {
    return ($rank + 1) . '.';
}

function create_element($name, $content = '', $attributes = array()) {
    $result = '<' . $name;
    foreach ($attributes as $key => $value)
        $result .= ' ' . $key . '="' . $value . '"';
    if (autoclose($name))
        $result .= ' /';
    $result .= '>';
    if (!autoclose($name)) {
        $result .= $content;
        $result .= $attributes['value'];
        $result .= '</' . $name . '>';
    }
    return $result;
}

?>
