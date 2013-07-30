<?php

import_lib('format');

function page_file($page) {
    return 'page' . $page;
}

function real_page($page) {
    global $args;
    while ($page != $args[0] && is_null(script(page_file($page)))) {
        $next_page = dirname($page);
        $page = $next_page . '/_';
        if (is_null(script(page_file($page))))
            $page = $next_page;
    }
    if ($page == $args[0])
        $page .= '404';
    return $page;
}

function init_page($page) {
    $file = page_file(real_page($page));
    if (file_exists(script($file)))
        import_once($file);
}

function section($section, $data = '') {
    global $s;
    $s['sections'][] = array($section, $data);
}

function body() {
    global $s;
    foreach ($s['sections'] as $section_data) {
        $section = $section_data[0];
        $s['d'] = $section_data[1];
        import('section/' . $section);
    }
}

function default_head() {
    global $s;

    echo '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
    echo '<title>' . $s['head'] . '</title>';
    echo '<meta name="keywords" content="' . $s['keywords'] . '">';
    echo '<meta name="description" content="' . $s['description'] . '">';
    if ($s['hide'])
        echo '<meta name="robots" content="noindex, nofollow">';
    echo '<style type="text/css">';
    $css = split_values($s['css']);
    foreach ($css as $sheet)
        import_raw('css/' . $sheet . '.css');
    echo '</style>';
    echo $s['analytics'];
}

header('Content-Type: text/html; charset=iso-8859-1');

$s['template'] = 'default';
$s['submenu'] = '';
$s['head'] = 'Unnamed page';
$s['keywords'] = $s['project'];
$s['description'] = '';
$s['sections'] = array();

init_page($args);

if ($s['head'] == '') {
    $s['head'] = $s['project'];
    $s['title'] = $s['head'];
    $s['head'] = strip_tags($s['head']);
} else {
    $s['title'] = $s['head'];
    $s['head'] = strip_tags($s['head']);
    $s['keywords'] = $s['head'] . ', ' . $s['keywords'];
    $s['head'] .= ' | ' . $s['project'];
}

import('templates/' . $s['template']);

?>
