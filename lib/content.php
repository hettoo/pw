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
    $file = page_file(real_page($page) . '_init');
    if (file_exists(script($file)))
        import_once($file);
}

function import_page($page) {
    import(page_file(real_page($page)));
}

function init_child_page($child) {
    global $args;
    init_page(real_page($args) . '/' . $child);
}

function import_child_page($child, $header = true) {
    global $args, $s, $hierarchy;
    $page = real_page($args) . '/' . $child;
    $hierarchy[1] = $child;
    if ($header) {
        init_page($page);
        echo '<h2>' . $s['head'] . '</h2>';
    }
    import_page($page);
}

function default_head() {
    global $s;

    echo '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
    echo '<title>' . $s['head'] . '</title>';
    echo '<meta name="keywords" content="' . $s['keywords'] . '">';
    echo '<meta name="description" content="' . $s['description'] . '">';
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
