<?php

$s['head'] = 'Admin config editor';
$s['description'] = 'Admin config editor.';

import_lib('common/admin');
if (is_null($s['admin']))
    return;

$s['suburl'] = array('key');

import_lib('Form');

$update = false;
$form = new Form('contact');
$clear = false;
$index = find_index('key');
if (isset($index)) {
    $key = $hierarchy[$index];
    if (isset($key)) {
        $update = true;
        $oldkey = secure($key);
        $result = query("SELECT `key`, `value` FROM `config` WHERE `key`='$key' LIMIT 1");
        if ($row = $result->fetch_array())
            $form->setData($row);
    }
}
if ($form->received()) {
    $key = secure($form->get('key'));
    $value = secure($form->get('value'));
    $query = " `config` SET `key`='$key', `value`='$value'";
    if ($update)
        query("UPDATE $query WHERE `key`='$oldkey'");
    else
        query("INSERT INTO $query");
    redirect_up();
}
$form->add('Key', 'text', 'key', false);
$form->add('Value', 'text', 'value', false);
$form->add('Submit', 'submit');

section('single', $form->format());

?>
