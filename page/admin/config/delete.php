<?php

import_lib('common/admin');
if (is_null($s['admin']))
    return;

$s['suburl'] = array('key');

$key = find_value('key');
if (isset($key))
    query("DELETE FROM `config` WHERE `key`=$key");
redirect_up();

?>
