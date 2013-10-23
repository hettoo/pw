<?php

list($prefix, list($title, $list)) = $s['d'];

?>
<h2><?= $title ?></h2>
<?php section('links', array($prefix . '/' . nicen($title), $list)) ?>
