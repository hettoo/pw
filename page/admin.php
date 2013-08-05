<?php

$s['head'] = 'Admin module overview';
$s['description'] = 'Admin module overview.';
$s['suburl'] = array('mode');

import_lib('common/admin');
if (!$s['admin_result'])
    return;

section('modules', array('admin', array(
    array('Users', array(
        array('', 'List users'),
        array('add', 'Add user')
    )),
    array('Pages', array(
        array('', 'List pages'),
        array('add', 'Add page')
    ))
)));

?>
