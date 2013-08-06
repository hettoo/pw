<?php

$s['member_level'] = 1;
$s['editor_level'] = 2;
$s['cooperator_level'] = 4;
$s['admin_level'] = 5;

add_module('Config', $s['admin_level'], array(
    array('list', 'List config'),
    array('add', 'Add config value')
));
add_module('Users', $s['cooperator_level'], array(
    array('list', 'List users'),
    array('add', 'Add user')
));
add_module('Pages', $s['editor_level'], array(
    array('list', 'List pages'),
    array('add', 'Add page')
));

?>
