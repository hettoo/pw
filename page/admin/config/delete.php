<?php

import_lib('common/admin');
if (is_null($s['admin']))
    return;

$s['suburl'] = array('key');

import_lib('Form');

$update = false;
$form = new Form('contact');
$clear = false;
$index = find_index('key');
if (isset($index)) {
    $key = $hierarchy[$index];
    if (isset($key))
        query("DELETE FROM `config` WHERE `key`='$key'");
}
redirect_up();

?>
