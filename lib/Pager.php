<?php

class Pager {
    private $index;
    private $page;
    private $limit;

    private $pages;
    private $rows;

    function __construct($limit = 20) {
        global $hierarchy;
        $this->index = find_index('page');
        $this->page = max((int)$hierarchy[$this->index] - 1, 0);
        $hierarchy[$this->index] = $this->page + 1;
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

    function query($fields, $table, $rest = '', $function = null, $args = null) {
        global $s;

        $skip = $this->getOffset();
        $result = query("SELECT COUNT(*) AS `count` FROM $table");
        $row = $result->fetch_array();
        $total = $row['count'];
        $this->rows = array();
        $result = query("SELECT $fields FROM `$table` $rest LIMIT $skip, $this->limit");
        for ($i = 0; $i < $row = $result->fetch_array(); $i++)
            $this->rows[] = $row;
        $this->pages = ceil($total / $this->limit);
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

    function format() {
        global $s;

        if (!$this->drawable())
            return '';

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

        $result = '';
        $result .= '<ul class="pager">';
        $result .= '<li><a href="' . url(1, $this->index, false) . '">&lt;&lt;</a></li>';
        $result .= '<li><a href="' . url(max($page - 1, 1), $this->index, false) . '">&lt;</a></li>';
        if ($start > 1)
            $result .= '...';
        for ($i = $start; $i <= $end; $i++)
            $result .= '<li' . ($i == $page ? ' class="active"' : '') . '><a href="' . url($i, $this->index, false) . '">' . $i . '</a></li>';
        if ($end < $pages)
            $result .= '...';
        $result .= '<li><a href="' . url(min($page + 1, $pages), $this->index, false) . '">&gt;</a></li>';
        $result .= '<li><a href="' . url($pages, $this->index, false) . '">&gt;&gt;</a></li>';
        $result .= '</ul>';
        return $result;
    }
}

?>
