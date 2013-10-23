<?php

import_lib('common/admin');
if (is_null($s['admin']))
    return;

action_page(function ($key) {
    $key = secure($key);
    query("DELETE FROM `" . prefix('config') . "` WHERE `key`='$key'");
});

?>
