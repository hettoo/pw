<?php

$search = $s['d'];
$form = $search->getForm();
$pager = $search->getPager();

?>
<div class="search">
<?php $form->show('default') ?>
<?php if (isset($pager)): ?>
<?php section('pager', $pager) ?>
<?php endif ?>
</div>
