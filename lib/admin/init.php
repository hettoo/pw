<?php

add_module('Config', 5, 'list', array(
    array('list', 'List config'),
    array('add', 'Add config value')
));
add_module('Users', 4, 'list', array(
    array('list', 'List users'),
    array('add', 'Add user')
));
add_module('Pages', 1, 'list', array(
    array('list', 'List pages'),
    array('add', 'Add page')
));

?>
