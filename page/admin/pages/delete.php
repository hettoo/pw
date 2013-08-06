<?php

import_lib('common/admin');
if (is_null($s['admin']))
    return;

$s['suburl'] = array('id');

$id = find_value('id');
if (isset($id)) {
    $id = (int)$id;
    query("DELETE FROM `page` WHERE `id`=$id");
}
redirect_up();

?>
