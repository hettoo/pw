<?php

import_lib('Account');

$type = $hierarchy[1];
$account = new Account($type);
$account->logout();
redirect_back();

?>
