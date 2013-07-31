<?php

import_lib('Account');

$s['head'] = 'Admin';
$s['description'] = 'Administration area.';
$s['hide'] = true;

$account = new Account('admin');
if ($account->login())
    section('logout', 'admin');

?>
