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
$section_count = 0;
$sections = array();
if (isset($id)) {
    $id = (int)$id;
    $data = array();
    $result = query("SELECT * FROM `page` WHERE `id`=$id LIMIT 1");
    if ($row = $result->fetch_array())
        $data = $row;
    $result = query("SELECT * FROM `content` WHERE `page`=$id ORDER BY `ranking`");
    $section_count = $result->num_rows;
    if ($section_count == 0) {
        $data['section_0'] = '';
        query("INSERT INTO `content` SET `page`=$id, `content`=''");
        $section_count++;
    }
    $data['sections'] = $section_count;
    $i = 0;
    while ($row = $result->fetch_array()) {
        $sections[] = $row;
        $data['section_' . $i++] = $row['content'];
    }
    $form->setData($data);
}
if ($form->received()) {
    $page = secure($form->get('page'));
    $query = " `page` SET `page`='$page'";
    $wanted = (int)$form->get('sections');
    $insert = $wanted - $section_count;
    if ($section_count > $wanted) {
        $delete = -$insert;
        query("DELETE FROM `content` WHERE `page`=$id ORDER BY `ranking` DESC, `id` DESC LIMIT $delete");
        $insert = 0;
    }
    $edit = $section_count;
    if (isset($id))
        query("UPDATE$query WHERE `id`=$id");
    else
        $result = query("INSERT INTO$query");
    $index = 0;
    for ($i = 0; $i < $edit; $i++) {
        $content = $form->get('section_' . $index);
        $cid = $sections[$i]['id'];
        query("UPDATE `content` SET `content`='$content' WHERE `id`=$cid");
        $index++;
    }
    for ($i = 0; $i < $insert; $i++) {
        $content = secure($form->get('section_' . $index));
        $page = isset($id) ? $id : $result->insert_id;
        query("INSERT INTO `content` SET `content`='$content', `page`=$page");
        $index++;
    }
    redirect_up();
}
$section_count = max(1, $section_count);
$form->add('Page', 'text', 'page');
$form->add('Sections', 'text', 'sections');
for ($i = 0; $i < $section_count; $i++)
    $form->add('Section ' . ($i + 1), 'textarea', 'section_' . $i, false);
if (isset($id))
    $form->add($id, 'hidden', 'id');
$form->add('Submit', 'submit');

section('form', $form);

?>
