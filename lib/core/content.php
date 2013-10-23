<?php

import_lib('utils/format');

function page_file($page) {
    return 'page/' . $page;
}

function real_page($page) {
    while ($page != '' && is_null(script(page_file($page)))) {
        $next_page = dirname($page);
        if ($next_page == '.')
            $next_page = '';
        $page = $next_page . '/_';
        if (is_null(script(page_file($page))))
            $page = $next_page;
    }
    if ($page == '')
        $page = '404';
    return $page;
}

function init_page($page, $single = false) {
    global $s;
    $s['c'] = array();
    $filtered = secure($page);
    $id = 0;
    if ($page != 'pw') {
        $result = query("SELECT * FROM `" . prefix('page') . "` WHERE `page`='$filtered'");
        if ($result && $row = $result->fetch_array()) {
            $s = array_merge($s, $row);
            $id = $row['id'];
            $result = query("SELECT `content` FROM `" . prefix('content') . "` WHERE `page`='$id' ORDER BY `ranking`");
            while ($row = $result->fetch_array())
                $s['c'][] = $row['content'];
        }
    }
    $real = real_page($page);
    if ($real == '404' && $id != 0 && $page != '404') {
        $real = 'default';
    } else if ($real == '404' && $page != '404') {
        init_page('404');
        return;
    }
    if ($page == '404')
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    $s['page'] = $real;
    $file = page_file($real);
    if (file_exists(script($file)))
        import_once($file);
}

function subpage($sub) {
    global $s;
    $s['h'] = explode('/', $s['page'] . '/' . $sub);
    init_page($s['page'] . '/' . $sub, true);
}

function customs() {
    global $s;
    return count($s['c']);
}

function wrap_section($id, $section, $data = '') {
    global $s;
    if ($s['sectioning']) {
        if (isset($id))
            echo '<div id="' . $id . '">';
        $s['d'] = $data;
        import('section/' . $section);
        if (isset($id))
            echo '</div>';
    } else {
        $s['sections'][] = array($id, $section, $data);
    }
}

function section($section, $data = '') {
    wrap_section(null, $section, $data);
}

function body() {
    global $s;
    if (empty($s['sections'])) {
        section('fallback');
    } else {
        foreach ($s['sections'] as $section_data)
            wrap_section($section_data[0], $section_data[1], $section_data[2]);
    }
}

function default_head() {
    global $s;

    echo '<meta charset="' . $s['charset'] . '">';
    echo '<title>' . $s['title'] . '</title>';
    echo '<meta name="keywords" content="' . $s['keywords'] . '">';
    echo '<meta name="description" content="' . $s['description'] . '">';
    echo '<link rel="shortcut icon" href="'
        . (is_null(pw_file(resource('favicon.ico'), true))
        ? theme_url('favicon.ico') : resource_url('favicon.ico')) . '" />';
    if ($s['hide'])
        echo '<meta name="robots" content="noindex, nofollow">';
    $css = split_values($s['css']);
    if ($s['inline']) {
        echo '<style type="text/css">';
        foreach ($css as $sheet)
            import_raw(theme_resource('css/' . $sheet . '.css'));
        echo '</style>';
    } else {
        foreach ($css as $sheet)
            echo '<link href="' . theme_url('css/' . $sheet . '.css') . '" rel="stylesheet" type="text/css" />';
    }
    echo $s['header'];
    echo $s['analytics'];
}

function page_menu($index) {
    global $s;
    $start = join('/', array_slice($s['h'], 0, $index));
    $result = query("SELECT `page`, `short_title` FROM `" . prefix('page') . "` WHERE `page` LIKE '$start/%'");
    if ($result && $result->num_rows) {
        $menu = array();
        $mainresult = query("SELECT `page`, `short_title` FROM `" . prefix('page') . "` WHERE `page`='$start'");
        if ($mainresult && $mainresult->num_rows && $row = $mainresult->fetch_array()) {
            $title = $row['short_title'];
            if (empty($title))
                $title = $s['h'][$index - 1];
            $menu[] = array('', $title);
        }
        while ($row = $result->fetch_array()) {
            $title = $row['short_title'];
            if (empty($title))
                $title = $row['page'];
            $menu[] = array(explode('/', $row['page'])[$index], $title);
        }
        return create_menu($index, $menu);
    }
    return '';
}

$s['submenu'] = '';
$s['head'] = 'Unnamed page';
$s['keywords'] = $s['project'];
$s['description'] = '';
$s['sections'] = array();
$s['sectioning'] = false;

init_page($s['args']);

header('Content-Type: text/html; charset=' . $s['charset']);

if (empty($s['submenu']))
    $s['submenu'] = page_menu(1);
if (empty($s['head']))
    $s['head'] = $s['project'];
if (empty($s['title']))
    $s['title'] = $s['head'];
$s['title'] = strip_tags($s['title']);
    $s['keywords'] = $s['title'] . (empty($s['keywords']) ? '' : ', ' . $s['keywords']);
if ($s['title'] != $s['project'])
    $s['title'] .= ' - ' . $s['project'];

$s['menu'] = parse_hash($s['menu']);

$s['sectioning'] = true;
section('main');

?>
