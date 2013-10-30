<?php

import_lib('utils/format');

function page_lib_file($page) {
    return 'page/' . $page;
}

function page_file($page) {
    return lib_file(page_lib_file($page));
}

function real_page($page) {
    if ($page == '')
        $page = 'index';
    while ($page != '' && is_null(script(page_file($page)))) {
        $next_page = dirname($page);
        if ($next_page == page_file('') || $next_page == '.')
            $next_page = '';
        $page = $next_page . '/_';
        if (is_null(script(page_file($page))))
            $page = $next_page;
    }
    if ($page == '')
        $page = '404';
    return $page;
}

function page_data($page) {
    $filtered = secure($page);
    $result = query("SELECT * FROM `" . prefix('page') . "` WHERE `page`='$filtered'");
    if ($result && $row = $result->fetch_array())
        return $row;
    return null;
}

function init_page($page, $single = false) {
    global $s;
    $s['c'] = array();
    $data = null;
    if ($page != 'pw')
        $data = page_data($page);
    $real = real_page($page);
    if ($real == '404' && $page != '404') {
        if (isset($data)) {
            $real = 'default';
        } else {
            init_page('404');
            return;
        }
    }
    if ($page == '404')
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    $s['page'] = $real;
    $file = page_file($real);
    if (file_exists(script($file)))
        import_lib(page_lib_file($real));
    if ($page != 'pw' && (!isset($data) || $real != 'default'))
        $data = page_data($real);
    if (isset($data)) {
        $s = array_merge($s, $data);
        $id = $s['id'];
        $result = query("SELECT `content` FROM `" . prefix('content') . "` WHERE `page`='$id' ORDER BY `ranking`");
        while ($row = $result->fetch_array())
            $s['c'][] = $row['content'];
    }
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

function css_file($sheet) {
    global $s;
    return 'css/' . $sheet . ($s['min_css'] ? '.min' : '') . '.css';
}

function js_file($script) {
    global $s;
    return 'js/' . $script . ($s['min_js'] ? '.min' : '') . '.js';
}

function default_head() {
    global $s;
    echo '<meta charset="' . $s['charset'] . '">';
    echo '<title>' . $s['title'] . '</title>';
    echo '<meta name="description" content="' . $s['description'] . '">';
    echo '<link rel="shortcut icon" href="'
        . (is_null(pw_file(resource('favicon.ico'), true))
        ? theme_url('favicon.ico') : resource_url('favicon.ico')) . '">';
    if ($s['hide'])
        echo '<meta name="robots" content="noindex, nofollow">';
    $s['css'] = array_unique($s['css']);
    $s['js'] = array_unique($s['js']);
    foreach ($s['css'] as $sheet)
        echo '<link href="' . theme_url(css_file($sheet)) . '" rel="stylesheet">';
    foreach ($s['js'] as $script)
        echo '<script src="' . resource_url(js_file($js)) . '"></script>';
    echo $s['header'];
    echo $s['analytics'];
}

function page_menu($index) {
    global $s;
    $start = implode('/', array_slice($s['h'], 0, $index));
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
$s['description'] = '';
$s['sections'] = array();
$s['sectioning'] = false;

init_page(implode('/', $s['h']));

header('Content-Type: text/html; charset=' . $s['charset']);

if (empty($s['submenu']))
    $s['submenu'] = page_menu(1);
if (empty($s['head']))
    $s['head'] = $s['project'];
if (empty($s['title']))
    $s['title'] = $s['head'];
$s['title'] = strip_tags($s['title']);
if ($s['title'] != $s['project'])
    $s['title'] .= ' - ' . $s['project'];

$s['menu'] = parse_hash($s['menu']);

$s['sectioning'] = true;
section('main');

?>
