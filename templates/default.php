<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php default_head(); ?>
</head>
<body>
        <div id="main">
            <div id="header">
            </div>
            <div id="first">
            </div>
            <div id="main-menu">
                <?= create_menu(0, $s['menu']); ?>
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
