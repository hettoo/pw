<?php

$s['head'] = 'Admin page foto editor';
$s['description'] = 'Admin page foto editor.';

import_lib('common/admin');
if (is_null($s['admin']))
    return;

import_lib('ImageUploader');

$s['suburl'] = array('id');
$id = (int)find_value('id');

$uploader = new ImageUploader('images', 'page_images');
$uploader->setFields(7);

$data = array();
$result = query("SELECT * FROM `" . prefix('page_images') . "` WHERE `page`=$id");
while ($row = $result->fetch_array())
    $data[] = $row;
$uploader->setData($data);

$files = $uploader->process(function () {
    global $s;
    $result = query("INSERT INTO `" . prefix('page_images') . "` () VALUES ()");
    $id = $s['db']->insert_id;
    return array('name' => $id, 'id' => $id);
});
if (!empty($files)) {
    foreach ($files as $file) {
        $name = secure($file['name']);
        $description = secure($file['description']);
        $image = $file['id'];
        query("UPDATE `" . prefix('page_images') . "` SET `page`=$id, `file`='$name', `description`='$description' WHERE `id`=$image");
    }

    $data = array();
    $result = query("SELECT * FROM `" . prefix('page_images') . "` WHERE `page`=$id");
    while ($row = $result->fetch_array())
        $data[] = $row;
    $uploader->setData($data);
}

admin_upper_urls(admin_actions(page_index(), $id));
$form->show();

?>
