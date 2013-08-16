<?php

$search = $s['d'];
$form = $search->getForm();
$pager = $search->getPager();

?>
<p>
<?php subsection('form', $form) ?>
<?php if (isset($pager)): ?>
<?php subsection('pager', $pager) ?>
<?php endif ?>
</p>
