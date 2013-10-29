<?php

$search = $s['d'];
$form = $search->getForm();
$pager = $search->getPager();

?>
<div class="search">
<div class="form">
<?php $form->show('inline') ?>
</div>
<?php if (isset($pager)): ?>
<?php section('pager', $pager) ?>
<?php endif ?>
</div>
