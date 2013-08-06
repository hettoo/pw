<?php

import_lib('common/admin');
if (is_null($s['admin']))
    return;

$s['admin']->logout();
redirect_back();

?>
