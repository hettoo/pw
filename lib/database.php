<?php

$s['db'] = new mysqli($s['host'], $s['user'], $s['password'], $s['database']) or die("Unable to connect to the database.");

$result = $s['db']->query("SELECT `key`, `value` FROM `config`") or die($s['db']->error);
while ($row = $result->fetch_array())
    $s[$row['key']] = $row['value'];

?>
