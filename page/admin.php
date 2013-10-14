<?php

$s['head'] = 'Admin module overview';
$s['description'] = 'Admin module overview.';
$s['suburl'] = array('mode');

import_lib('common/admin');
if (is_null($s['admin']))
    return;

$urls = action_list(page_index() + 1, array('logout'));

admin_upper_urls($urls);
list_modules();
admin_lower_urls($urls);

?>
