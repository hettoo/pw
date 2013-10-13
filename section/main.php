<!doctype html>
<html lang="en">
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
                <h1><?= $s['head']; ?></h1>
            </div>
            <div id="sub-menu">
                <?= $s['submenu']; ?>
            </div>
            <div id="content">
                <?php body(); ?>
            </div>
        </div>
</body>
</html>
