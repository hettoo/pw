<?php

$s['head'] = 'Admin user editor';
$s['description'] = 'Admin user editor.';

import_lib('common/admin');
if (is_null($s['admin']))
    return;

import_lib('Form');

$s['suburl'] = array('id');

$form = new Form();
$id = find_value('id');
if (isset($id)) {
    $id = (int)$id;
    $result = query("SELECT * FROM `admin` WHERE `id`=$id LIMIT 1");
    if ($row = $result->fetch_array()) {
        unset($row['password']);
        $form->setData($row);
    }
}
if ($form->received()) {
    $name = secure($form->get('name'));
    $change_password = !isset($id) || $form->get('change_password');
    $password = secure($form->get('password'));
    $level = (int)$form->get('level');
    $email = secure($form->get('email'));
    $query = " `admin` SET `name`='$name', `level`=$level, `email`='$email'";
    if ($change_password)
        $query .= ", `password`=MD5('$password')";
    if (isset($id))
        query("UPDATE$query WHERE `id`=$id");
    else
        query("INSERT INTO$query");
    redirect_up();
}
$form->add('Name', 'text', 'name');
if (isset($id))
    $form->add('Change password', 'checkbox', 'change_password');
$form->add('Password', 'password', 'password', !isset($id));
$form->add('Level', 'text', 'level');
$form->add('E-mail', 'text', 'email');
if (isset($id))
    $form->add($id, 'hidden', 'id');
$form->add('Submit', 'submit');

$urls = admin_actions(page_index(), $id);
section('clean', $urls);
section('form', $form);
section('clean', $urls);

?>
