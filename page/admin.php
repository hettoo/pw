<?php

$s['head'] = 'Admin module overview';
$s['description'] = 'Admin module overview.';
$s['suburl'] = array('mode');

import_lib('common/admin');
if (is_null($s['admin']))
    return;

section('modules', array('admin', array(
    array('Users', array(
        array('list', 'List users'),
        array('add', 'Add user')
    )),
    array('Pages', array(
        array('list', 'List pages'),
        array('add', 'Add page')
    ))
)));

?>
