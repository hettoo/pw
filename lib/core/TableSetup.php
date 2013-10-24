<?php

class TableSetup {
    private $table;
    private $columns;

    function __construct($table) {
        $this->table = $table;
    }

    function add($column, $type) {
        $this->columns[$column] = $type;
    }

    function setup($tidy = false) {
        global $s;
        $result = query("SELECT `COLUMN_NAME` FROM `information_schema`.`COLUMNS` WHERE `TABLE_SCHEMA` = '"
            . $s['database'] . "' AND TABLE_NAME = '$this->table'");
        $existing = array();
        while ($row = $result->fetch_array())
            $existing[] = $row['COLUMN_NAME'];
        if (empty($existing)) {
            $query = "CREATE TABLE IF NOT EXISTS `$this->table` (";
            $first = true;
            foreach ($this->columns as $column => $type) {
                if ($first)
                    $first = false;
                else
                    $query .= ', ';
                $query .= "`$column` $type";
            }
            $query .= ')';
            query($query);
        } else {
            $columns = array();
            foreach ($this->columns as $column => $type) {
                if (in_array($column, $existing)) {
                    if (!preg_match('/primary\s+key/i', $type))
                        query("ALTER TABLE `$this->table` MODIFY `$column` $type");
                } else {
                    query("ALTER TABLE `$this->table` ADD `$column` $type");
                }
                $columns[] = $column;
            }
            if ($tidy) {
                $remove = array_diff($existing, $columns);
                foreach ($remove as $column)
                    query("ALTER TABLE `$this->table` DROP COLUMN `$column`");
            }
        }
    }
}

?>
