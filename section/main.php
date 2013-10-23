<!doctype html>
<html lang="en">
<head>
<?php default_head(); ?>
</head>
<body>
        <div id="main">
            <div id="header"></div>
            <div id="first"></div>
            <div id="content">
                <h1><?= $s['head'] ?></h1>
                <?php body(); ?>
            </div>
            <div id="main-menu"><?= create_menu(0, $s['menu']); ?></div>
            <div id="sub-menu"><?= $s['submenu']; ?></div>
        </div>
</body>
</html>
