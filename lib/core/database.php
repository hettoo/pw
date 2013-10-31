<?php

function connect($host, $user, $password, $database, $id = 'db') {
    global $s;
    $s[$id] = new mysqli($host, $user, $password, $database) or die("Unable to connect to $id.");
}

function load_db($id = 'db') {
    global $s;

    connect($s['host'], $s['user'], $s['password'], $s['database'], $id);

    $result = $s[$id]->query("SELECT `key`, `value` FROM `" . prefix('config') . "`");
    if ($result) {
        while ($row = $result->fetch_array())
            $s[$row['key']] = $row['value'];
    }
}

function query($query, $id = 'db') {
    global $s;
    if (!$s[$id])
        return null;
    $result = $s[$id]->query($query) or die($s[$id]->error);
    return $result;
}

function secure($variable, $mode = 'sql', $id = 'db') {
    global $s;
    $result = $variable;
    if (!$s[$id])
        return $variable;
    if ($mode == 'sql')
        $result = $s[$id]->real_escape_string($result);
    elseif ($mode == 'html')
        $result = htmlspecialchars($result);
    return $result;
}

function prefix($table) {
    global $s;
    return $s['prefix'] . $table;
}

function update_insert($query, $field, $value, $string = false, $id = 'db') {
    if (isset($value)) {
        if ($string)
            $value = "'" . secure($value, 'sql', $id) . "'";
        query("UPDATE$query WHERE `$field`=$value", $id);
    } else {
        query("INSERT INTO$query", $id);
    }
}

?>
