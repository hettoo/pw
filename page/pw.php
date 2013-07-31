<?php

import_lib('Form');
import_lib('KeyGen');

$s['head'] = 'PW Configuration';
$s['description'] = 'PW needs to know about its database.';

section('single', 'PW needs to know about its database.');

$error = false;
if (file_exists('setup/key')) {
    $key = file_get_contents('setup/key');
} else {
    $key = (new KeyGen())->generate(16);
    $fp = fopen('setup/key', 'w');
    if ($fp) {
        fwrite($fp, $key);
        fclose($fp);
    } else {
        section('single', 'Unable to write a key file. Make sure the setup directory is writable.');
        $error = true;
    }
}

if (!$error) {
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
        $s['host'] = $form_setup->get('host');
        $s['database'] = $form_setup->get('database');
        $s['user'] = $form_setup->get('user');
        $s['password'] = $form_setup->get('password');
        load_db();
        import_lib('core/setup');
        $fp = fopen('setup/setup', 'w');
        write_line($fp, $form_setup->get('host'));
        write_line($fp, $form_setup->get('database'));
        write_line($fp, $form_setup->get('user'));
        write_line($fp, $form_setup->get('password'));
        section('single', 'Setup saved!');
    }
}

?>
