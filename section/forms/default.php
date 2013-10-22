<?php

$form = $s['d'];
$class = $form->getClass();
$elements = $form->getElements();
$table = false;

?>
<form action="<?= this_url() ?>" method="POST" enctype="multipart/form-data"<?= isset($class) ? ' class="' . $class . '"' : '' ?>>
<?php foreach ($elements as $element): ?>
<?= $element ?>
<?php endforeach ?>
<input type="hidden" name="_form_id" value="<?= $form->getId() ?>" />
</form>
