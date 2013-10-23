<?php

list($prefix, $modules) = $s['d'];

?>
<?php foreach ($modules as $module): ?>
<?php section('module', array($prefix, $module)) ?>
<?php endforeach ?>
