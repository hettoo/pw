<?php

$s['head'] = '404';
$s['description'] = 'Page not found.';

if (customs())
    section('custom');
else
    section('single', 'Page not found.');

?>
