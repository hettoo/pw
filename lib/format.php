<?php

function create_menu($level, $menu) {
    global $hierarchy;
    $result = '';
    foreach ($menu as $item) {
        if (is_array($item)) {
            $link = $item[0];
            $name = $item[1];
        } else {
            $link = strtolower($item);
            $name = $item;
        }
        $result .= '<div';
        if ($hierarchy[$level] == (empty($link) ? 'index' : $link))
            $result .= ' class="active"';
        $result .= '><a href="' . url($link, $level) . '">' . $name . '</a></div>';
    }
    return $result;
}

function format_date_relative($time) {
    return '<span class="time">' . relative_time($time) . ($time == 0 ? '' : '<span class="exacttime">' . exact_datetime($time) . '</span>') . '</span>';
}

function format_date($time) {
    return '<span class="time">' . exact_date($time) . ($time == 0 ? '' : '<span class="exacttime">' . exact_time($time) . '</span>') . '</span>';
}

function format_time($time, $map) {
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
    if (!file_exists("./demos/$map.wd15"))
        return $result;
    return '<a href="' . resource_url("demos/$map.wd15") . '" target="_blank">' . $result . '</a>';
}

function format_rank($rank) {
    return ($rank + 1) . '.';
}

?>
