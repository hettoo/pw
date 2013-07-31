<?php

import_lib('core/session');
import_lib('Account');

$s['head'] = 'Admin';
$s['description'] = 'Administration area.';
$s['hide'] = true;

$account = new Account('admin');
$account->login();

?>
