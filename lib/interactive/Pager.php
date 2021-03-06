<?php

import_lib('utils/MultiFormat');

class Pager extends MultiFormat {
    private $index;
    private $page;
    private $limit;

    private $pages;
    private $rows;
    private $total;

    function __construct($limit = 20) {
        parent::__construct('pager');
        global $s;
        $this->index = find_index('page');
        $this->page = max((int)$s['h'][$this->index] - 1, 0);
        $s['h'][$this->index] = $this->page + 1;
        $this->limit = (int)$limit;
    }

    function getIndex() {
        return $this->index;
    }

    function getOffset() {
        return $this->page * $this->limit;
    }

    function getPage() {
        return $this->page;
    }

    function getDisplayed() {
        return count($this->rows);
    }

    function getTotal() {
        return $this->total;
    }

    function query($fields, $rest, $function = null, $args = null, $db_id = 'db') {
        global $s;

        $skip = $this->getOffset();
        $this->rows = array();
        $result = query("SELECT SQL_CALC_FOUND_ROWS $fields FROM $rest LIMIT $skip, $this->limit", $db_id);
        for ($i = 0; $i < $row = $result->fetch_array(); $i++)
            $this->rows[] = $row;
        $result = query("SELECT FOUND_ROWS() AS `count`", $db_id);
        $row = $result->fetch_array();
        $this->total = $row['count'];
        $this->pages = ceil($this->total / $this->limit);
        if (isset($function)) {
            foreach ($this->rows as $row)
                $function($row, $args);
        }
    }

    function getRows() {
        return $this->rows;
    }

    function getPages() {
        return $this->pages;
    }

    function drawable() {
        return $this->getPages() > 1;
    }

    function getRange() {
        global $s;
        $pages = $this->getPages();
        $page = $this->getPage() + 1;
        $start = 1;
        $end = $pages;
        $max_left = floor(($s['max_pages'] - 1) / 2);
        $max_right = ceil(($s['max_pages'] - 1) / 2);
        $fit_left = $page - $start;
        $fit_right = $end - $page;
        $missed_left = max(0, $max_left - $fit_left);
        $missed_right = max(0, $max_right - $fit_right);
        $left = min($fit_left, $max_left + $missed_right);
        $right = min($fit_right, $max_right + $missed_left);
        $start = $page - $left;
        $end = $page + $right;
        return array($start, $end);
    }
}

?>
