<?php

list($prefix, list($title, $list)) = $s['d'];

?>
<div class="module">
<h2><?= $title ?></h2>
<?php subsection('links', array($prefix . '/' . nicen($title), $list)) ?>
</div>
