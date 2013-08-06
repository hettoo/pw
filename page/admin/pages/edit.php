<?php

$s['head'] = 'Admin page editor';
$s['description'] = 'Admin page editor.';

import_lib('common/admin');
if (is_null($s['admin']))
    return;

import_lib('Form');

$s['suburl'] = array('id');

$form = new Form();
$id = find_value('id');
if (isset($id)) {
    $id = (int)$id;
    $result = query("SELECT * FROM `page` WHERE `id`=$id LIMIT 1");
    if ($row = $result->fetch_array())
        $form->setData($row);
}
if ($form->received()) {
    $page = secure($form->get('page'));
    $query = " `page` SET `page`='$page'";
    if (isset($id))
        query("UPDATE$query WHERE `id`=$id");
    else
        query("INSERT INTO$query");
    redirect_up();
}
$form->add('Page', 'text', 'page');
if (isset($id))
    $form->add($id, 'hidden', 'id');
$form->add('Submit', 'submit');

section('single', $form->format());

?>
