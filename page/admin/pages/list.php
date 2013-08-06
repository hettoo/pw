<?php

$s['head'] = 'Admin page list';
$s['description'] = 'Admin page list.';

import_lib('common/admin');
if (is_null($s['admin']))
    return;

import_lib('Table');

$s['suburl'] = array('order', 'page', 'search');
$action_index = page_index();
$urls = action_list($action_index, array(array('edit', 'add')));

section('single', $urls);
section('single', $urls);

?>
