<?php

function load_db() {
    global $s;

    $s['db'] = new mysqli($s['host'], $s['user'], $s['password'], $s['database']) or die("Unable to connect to the database.");

    $result = $s['db']->query("SELECT `key`, `value` FROM `config`");
    if ($result) {
        while ($row = $result->fetch_array())
            $s[$row['key']] = $row['value'];
    }
}

function query($query) {
    global $s;
    if (!$s['db'])
        return null;
    $result = $s['db']->query($query) or die($s['db']->error);
    return $result;
}

function secure($variable, $mode = 'sql') {
    global $s;
    $result = $variable;
    if (!$s['db'])
        return $variable;
    if ($mode == 'sql')
        $result = $s['db']->real_escape_string($result);
    elseif ($mode == 'html')
        $result = htmlspecialchars($result);
    return $result;
}

?>
