<?php

$s['head'] = 'PW Configuration';
$s['description'] = 'PW needs to know about its database.';

if (file_exists('setup/key')) {
    $s['key'] = file_get_contents('setup/key');
} else {
    $chars = '1234567890qwertyuiopasdfghjklzxcvbnm';
    $length = strlen($chars);
    $s['key'] = '';
    for ($i = 0; $i < 16; $i++)
        $s['key'] .= $chars[rand() % $length];
    $fp = fopen('setup/key', 'w');
    fwrite($fp, $s['key']);
    fclose($fp);
}

?>
