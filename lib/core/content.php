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
    $result = query("SELECT * FROM `page` WHERE `page`='$filtered'");
    if ($result && $row = $result->fetch_array()) {
        $s = array_merge($s, $row);
        $id = $row['id'];
        $result = query("SELECT `content` FROM `content` WHERE `page`='$id' ORDER BY `ranking`");
        while ($row = $result->fetch_array())
            $s['c'][] = $row['content'];
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
    global $s, $hierarchy;
    $hierarchy = explode('/', $s['page'] . '/' . $sub);
    init_page($s['page'] . '/' . $sub, true);
}

function customs() {
    global $s;
    return count($s['c']);
}

function wrap_section($id, $section, $data = '') {
    global $s;
    $s['sections'][] = array($id, $section, $data);
}

function section($section, $data = '') {
    wrap_section(null, $section, $data);
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
        foreach ($s['sections'] as $section_data) {
            if (isset($section_data[0]))
                echo '<div id="' . $section_data[0] . '">';
            subsection($section_data[1], $section_data[2]);
            if (isset($section_data[0]))
                echo '</div>';
        }
    }
}

function default_head() {
    global $s;

    echo '<meta charset="' . $s['charset'] . '">';
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
    echo $s['header'];
    echo $s['analytics'];
}

$s['submenu'] = '';
$s['head'] = 'Unnamed page';
$s['keywords'] = $s['project'];
$s['description'] = '';
$s['sections'] = array();

init_page($args);

header('Content-Type: text/html; charset=' . $s['charset']);

if (empty($s['head'])) {
    $s['title'] = $s['head'];
    $s['head'] = strip_tags($s['head']);
    $s['keywords'] = $s['head'] . (empty($s['keywords'] ? '' : ', ' . $s['keywords']));
    $s['head'] .= ' - ' . $s['project'];
} else {
    $s['title'] = $s['project'];
    $s['head'] = strip_tags($s['title']);
}

$s['menu'] = parse_hash($s['menu']);

subsection('main');

?>
