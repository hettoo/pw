<?php

import_lib('Form');

$s['head'] = 'Admin';
$s['description'] = 'Administration area.';
$s['hide'] = true;

$form = new Form('admin');
$form->add('Name', 'text', 'name');
$form->add('Password', 'password', 'password');
$form->add('Submit', 'submit');

$s['form'] = $form;

?>
