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

function init_page($page) {
    global $s;
    $s['c'] = array();
    $filtered = secure($page);
    $id = 0;
    $result = query("SELECT * FROM `page` WHERE `page`='$filtered'");
    if ($result && $row = $result->fetch_array()) {
        $s = array_merge($s, $row);
        $id = $row['id'];
        $result = query("SELECT `content` FROM `content` WHERE `page`='$id'");
        while ($row = $result->fetch_array()) {
            $s['c'][] = $row['content'];
        }
    }
    $real = real_page($page);
    if ($real == '404' && $id != 0) {
        if ($page == '404')
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    } else if ($real != '404' || $page == '404') {
        if ($page == '404')
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        $file = page_file($real);
        if (file_exists(script($file)))
            import_once($file);
    } else {
        init_page('404');
    }
}

function customs() {
    global $s;
    return count($s['c']);
}

function section($section, $data = '') {
    global $s;
    $s['sections'][] = array($section, $data);
}

function subsection($section, $data = '') {
    global $s;
    $s['d'] = $data;
    import('section/' . $section);
}

function body() {
    global $s;
    if (empty($s['sections'])) {
        subsection('fallback');
    } else {
        foreach ($s['sections'] as $section_data)
            subsection($section_data[0], $section_data[1]);
    }
}

function default_head() {
    global $s;

    echo '<meta http-equiv="Content-Type" content="text/html; charset=' . $s['charset'] . '">';
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

$s['template'] = 'default';
$s['submenu'] = '';
$s['head'] = 'Unnamed page';
$s['keywords'] = $s['project'];
$s['description'] = '';
$s['sections'] = array();

init_page($args);

header('Content-Type: text/html; charset=' . $s['charset']);

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

$s['menu'] = parse_menu($s['menu']);

import('templates/' . $s['template']);

?>
