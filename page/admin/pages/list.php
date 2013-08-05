<?php

$s['head'] = 'Admin page list';
$s['description'] = 'Admin page list.';

import_lib('common/admin');
if (is_null($s['admin']))
    return;

import_lib('Table');
import_lib('Search');
import_lib('Pager');

$s['suburl'] = array('order', 'page', 'search');

?>
