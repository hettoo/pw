<?php

list($prefix, $modules) = $s['d'];

?>
<?php foreach ($modules as $module): ?>
<?php subsection('module', array($prefix, $module)) ?>
<?php endforeach ?>
