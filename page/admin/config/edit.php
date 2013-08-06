<?php

$s['head'] = 'Admin config editor';
$s['description'] = 'Admin config editor.';

import_lib('common/admin');
if (is_null($s['admin']))
    return;

import_lib('Form');

$s['suburl'] = array('key');

$form = new Form();
$key = find_value('key');
if (isset($key)) {
    $oldkey = secure($key);
    $result = query("SELECT * FROM `config` WHERE `key`='$oldkey' LIMIT 1");
    if ($row = $result->fetch_array())
        $form->setData($row);
}
if ($form->received()) {
    $key = secure($form->get('key'));
    $value = secure($form->get('value'));
    $query = " `config` SET `key`='$key', `value`='$value'";
    if (isset($oldkey))
        query("UPDATE$query WHERE `key`='$oldkey'");
    else
        query("INSERT INTO$query");
    redirect_up();
}
$form->add('Key', 'text', 'key');
$form->add('Value', 'text', 'value', false);
$form->add('Submit', 'submit');

section('single', $form->format());

?>
