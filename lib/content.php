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

header('Content-Type: text/html; charset=iso-8859-1');

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
$main_menu = create_menu(0, $s['menu']);
$css = split_values($s['css']);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title><?= $s['head']; ?></title>
    <meta name="keywords" content="<?= $s['keywords']; ?>">
    <meta name="description" content="<?= $s['description']; ?>">
    <style type="text/css">
<?php
foreach ($css as $sheet)
    import_raw('css/' . $sheet . '.css');
?>
    </style>
    <?= $s['analytics']; ?>
</head>
<body>
        <div id="main">
            <div id="header">
            </div>
            <div id="first">
            </div>
            <div id="main-menu">
                <?= $main_menu; ?>
            </div>
            <div class="clear"></div>
            <div id="head">
                <h1><?= $s['title']; ?></h1>
            </div>
            <div id="sub-menu">
                <?= $s['submenu']; ?>
            </div>
            <div id="content">
                <?php import_page($args); ?>
            </div>
        </div>
</body>
</html>
