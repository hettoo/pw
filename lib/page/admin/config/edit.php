<?php

$s['head'] = 'Admin config editor';
$s['description'] = 'Admin config editor.';

import_lib('common/admin');
if (is_null($s['admin']))
    return;

import_lib('interactive/Form');

$s['suburl'] = array('key');

$form = new Form();
$key = find_value('key');
if (isset($key)) {
    $oldkey = secure($key);
    $result = query("SELECT * FROM `" . prefix('config') . "` WHERE `key`='$oldkey' LIMIT 1");
    if ($row = $result->fetch_array())
        $form->setData($row);
}
$form->add('Key', 'text', 'key');
$form->add('Value', 'text', 'value', false);
$form->add('Submit', 'submit');
if ($form->received() && $form->check()) {
    $key = secure($form->get('key'));
    $value = secure($form->get('value'));
    $query = " `" . prefix('config') . "` SET `key`='$key', `value`='$value'";
    update_insert($query, 'key', $oldkey, true);
    redirect_up();
}

admin_upper_urls(admin_actions(page_index(), $id));
$form->show();

?>
