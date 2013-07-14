<?php

import_lib('format');

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
