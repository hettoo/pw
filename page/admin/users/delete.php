<?php

import_lib('common/admin');
if (is_null($s['admin']))
    return;

action_page(function ($id) {
    $id = (int)$id;
    query("DELETE FROM `" . prefix('admin') . "` WHERE `id`=$id");
});

?>
