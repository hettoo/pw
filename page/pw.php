<?php

import_lib('Form');

$s['head'] = 'PW Configuration';
$s['description'] = 'PW needs to know about its database.';

section('single', 'PW needs to know about its database.');

if (file_exists('setup/key')) {
    $key = file_get_contents('setup/key');
} else {
    $chars = '1234567890qwertyuiopasdfghjklzxcvbnm';
    $length = strlen($chars);
    $key = '';
    for ($i = 0; $i < 16; $i++)
        $key .= $chars[rand() % $length];
    $fp = fopen('setup/key', 'w');
    if ($fp) {
        fwrite($fp, $key);
        fclose($fp);
    } else {
        section('single', 'Unable to write a key file.');
    }
}

$form_key = new Form('key');
$form_setup = new Form('setup');
if ((!$form_key->received() || $form_key->get('key') != $key) && (!$form_setup->received() || $form_setup->get('key') != $key)) {
    section('single', 'To be able to set this up, enter the code found in the key file in the setup folder of the project in the box below.');
    $form_key->add('Key', 'text', 'key');
    $form_key->add('Submit', 'submit');
    section('single', $form_key->format());
} elseif (!$form_setup->received()) {
    $form_setup->setData(array('host' => $s['host'], 'database' => $s['database'], 'user' => $s['user'], 'password' => $s['password']));
    $form_setup->add('Host', 'text', 'host');
    $form_setup->add('Database', 'text', 'database');
    $form_setup->add('User', 'text', 'user');
    $form_setup->add('Password', 'password', 'password');
    $form_setup->add($key, 'hidden', 'key');
    $form_setup->add('Submit', 'submit');
    section('single', $form_setup->format());
} else {
    import_lib('core/setup');
    $fp = fopen('setup/setup', 'w');
    write_line($fp, $form_setup->get('host'));
    write_line($fp, $form_setup->get('database'));
    write_line($fp, $form_setup->get('user'));
    write_line($fp, $form_setup->get('password'));
    section('single', 'Setup saved!');
}

?>
