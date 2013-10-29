<?php

$s['head'] = 'Admin page editor';
$s['description'] = 'Admin page editor.';

import_lib('common/admin');
if (is_null($s['admin']))
    return;

import_lib('interactive/Form');

$s['suburl'] = array('id');

$form = new Form();
$id = find_value('id');
$section_count = 0;
$sections = array();
if (isset($id)) {
    $id = (int)$id;
    $data = array();
    $result = query("SELECT * FROM `" . prefix('page') . "` WHERE `id`=$id LIMIT 1");
    if ($row = $result->fetch_array())
        $data = $row;
    $result = query("SELECT * FROM `" . prefix('content') . "` WHERE `page`=$id ORDER BY `ranking`");
    $section_count = $result->num_rows;
    if ($section_count == 0) {
        $data['section_0'] = '';
        query("INSERT INTO `" . prefix('content') . "` SET `page`=$id, `content`=''");
        $section_count++;
    }
    $i = 0;
    while ($row = $result->fetch_array()) {
        $sections[] = $row;
        $data['section_' . $i++] = $row['content'];
    }
    $form->setData($data);
}
$wanted = 1;
$form->add('URL', 'text', 'page', false);
$form->add('Short title', 'text', 'short_title', false);
$form->add('Head', 'text', 'head', false);
for ($i = 0; $i < $wanted; $i++)
    $form->add('Section ' . ($i + 1), 'textarea', 'section_' . $i, false);
$form->add('Title', 'text', 'title', false);
$form->add('Description', 'text', 'description', false);
if (isset($id))
    $form->add($id, 'hidden', 'id');
$form->add('Save', 'submit');
$form->add('Save and return', 'submit', 'return');
if ($form->received() && $form->check()) {
    $page = secure($form->get('page'));
    $head = secure($form->get('head'));
    $title = secure($form->get('title'));
    $short_title = secure($form->get('short_title'));
    $description = secure($form->get('description'));
    $query = " `" . prefix('page') . "` SET `page`='$page', `head`='$head', `title`='$title', `short_title`='$short_title', `description`='$description'";
    $insert = $wanted - $section_count;
    if ($section_count > $wanted) {
        $delete = -$insert;
        query("DELETE FROM `" . prefix('content') . "` WHERE `page`=$id ORDER BY `ranking` DESC, `id` DESC LIMIT $delete");
        $insert = 0;
    }
    $edit = $section_count;
    update_insert($query, 'id', $id);
    $index = 0;
    for ($i = 0; $i < $edit; $i++) {
        $content = secure($form->get('section_' . $index));
        $cid = $sections[$i]['id'];
        query("UPDATE `" . prefix('content') . "` SET `content`='$content' WHERE `id`=$cid");
        $index++;
    }
    for ($i = 0; $i < $insert; $i++) {
        $content = secure($form->get('section_' . $index));
        $page = isset($id) ? $id : $s['db']->insert_id;
        query("INSERT INTO `" . prefix('content') . "` SET `content`='$content', `page`=$page");
        $index++;
    }
    if ($form->get('return'))
        redirect_up();
    $section_count = $wanted;
}

admin_upper_urls(admin_actions(page_index(), $id));
$form->show();

?>
