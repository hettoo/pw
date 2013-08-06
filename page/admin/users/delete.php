<?php

import_lib('common/admin');
if (is_null($s['admin']))
    return;

$s['suburl'] = array('id');

$id = find_value('id');
if (isset($id))
    query("DELETE FROM `admin` WHERE `id`=$id");
redirect_up();

?>
