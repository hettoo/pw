<?php

$form = $s['d'];
$class = $form->getClass();
$elements = $form->getElements();
$errors = $form->getErrors();
$table = false;

?>
<?php section('error', $errors) ?>
<form action="<?= this_url() ?>" method="POST" enctype="multipart/form-data"<?= isset($class) ? ' class="' . $class . '"' : '' ?>>
<?php
foreach ($elements as $element) {
    list($title, $obligatory, $content) = $element;
    if (isset($title)) {
        if (!$table) {
            $table = true;
            echo '<table>';
        }
        echo '<tr><td>' . $title . ($obligatory ? ' <span class="obligatory">*</span>' : '') . '</td><td>' . $content . '</td></tr>';
    } else {
        if ($table) {
            $table = false;
            echo '</table>';
        }
        echo $content;
    }
}
if ($table)
    echo '</table>';
?>
<input type="hidden" name="_form_id" value="<?= $form->getId() ?>" />
</form>
