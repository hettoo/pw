<?php

$s['member_level'] = 1;
$s['editor_level'] = 2;
$s['cooperator_level'] = 4;
$s['admin_level'] = 5;

add_module('Config', $s['admin_level'], array(
    array('list', 'List config'),
    array('edit', 'Add config value')
), array(
    array('edit', 'Edit config'),
    array('delete', 'Delete config')
));
add_module('Users', $s['cooperator_level'], array(
    array('list', 'List users'),
    array('edit', 'Add user')
), array(
    array('edit', 'Edit user'),
    array('delete', 'Delete user')
));
add_module('Pages', $s['editor_level'], array(
    array('list', 'List pages'),
    array('edit', 'Add page')
), array(
    array('edit', 'Edit page'),
    array('fotos', 'Edit fotos'),
    array('delete', 'Delete page')
));

?>
