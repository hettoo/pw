<?php

import_lib('session');
import_lib('Form');

$s['head'] = 'Admin';
$s['description'] = 'Administration area.';
$s['hide'] = true;

if (!$_SESSION['auth']) {
    $form = new Form('admin');
    $form->add('Name', 'text', 'name');
    $form->add('Password', 'password', 'password');
    $form->add('Submit', 'submit');
    section('single', $form->format());
}

?>
