<?php

$s['head'] = 'Admin module overview';
$s['description'] = 'Admin module overview.';
$s['suburl'] = array('mode');

import_lib('common/admin');
if (is_null($s['admin']))
    return;

$action_index = page_index() + 1;
$urls = action_list($action_index, array('logout'));

section('clean', $urls);
list_modules();
section('clean', $urls);

?>
