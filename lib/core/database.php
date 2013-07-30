<?php

function query($query) {
    global $s;
    $result = $s['db']->query($query) or die($s['db']->error);
    return $result;
}

$s['db'] = new mysqli($s['host'], $s['user'], $s['password'], $s['database']) or die("Unable to connect to the database.");

$result = $s['db']->query("SELECT `key`, `value` FROM `config`");
if ($result) {
    while ($row = $result->fetch_array())
        $s[$row['key']] = $row['value'];
}

?>
