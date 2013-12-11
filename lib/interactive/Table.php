<?php

import_lib('interactive/Pager');
import_lib('interactive/Search');
import_lib('utils/MultiFormat');

class Table extends MultiFormat {
    private $columns;
    private $order_index;
    private $pager;
    private $search;
    private $head;
    private $force_columns;
    private $empty_message;

    private $content;
    private $x;

    private $column;
    private $table;
    private $further_order;
    private $descending;

    function __construct() {
        parent::__construct('table');
        $this->content = '';
        $this->x = 0;
        $this->pager = null;
        $this->search = null;
        $this->columns = array();
        $this->column = null;
        $this->order_index = find_index('order');
        $this->head = true;
        $this->force_columns = 1;
        $this->empty_message = 'No data found.';
    }

    function setEmptyMessage($message) {
        $this->empty_message = $message;
    }

    function getEmptyMessage() {
        return $this->empty_message;
    }

    function getSearch() {
        return $this->search;
    }

    function getPager() {
        return $this->pager;
    }

    function getOrderIndex() {
        return $this->order_index;
    }

    function getHead() {
        return $this->head;
    }

    function getColumns() {
        return $this->columns;
    }

    function forceColumns($amount) {
        $this->head = false;
        $this->force_columns = $amount;
    }

    function addColumn($values) {
        if (!isset($values['name']))
            $values['name'] = nicen($values['title']);
        if (!isset($values['column']))
            $values['column'] = $values['name'];
        $this->columns[] = $values;
    }

    function getOrdering($default = null) {
        global $s;
        $order = secure($s['h'][$this->order_index]);
        if (isset($default)) {
            if ($order == '')
                $order = $default;
            $s['h'][$this->order_index] = $order;
        }
        $descending = substr($order, -1) == '-';
        if ($descending)
            $order = substr($order, 0, -1);
        return array($order, $descending);
    }

    function processOrder($default_order = 'name') {
        $table = '';
        list($order, $this->descending) = $this->getOrdering($default_order);
        foreach ($this->columns as $values) {
            if ($values['column'] == $order && !$values['no-order']) {
                $this->column = $values['column'];
                $this->table = $values['table'];
                $this->further_order = $values['further-order'];
                if (!isset($this->further_order))
                    $this->further_order = array();
                elseif (!is_array($this->further_order))
                    $this->further_order = array($this->further_order);
            }
        }
    }

    function getOrder() {
        $result = '';
        if (!isset($this->column))
            return $result;
        $result .= ' ORDER BY `' . (isset($this->table) ? $this->table . '`.`' : '') . $this->column . '` ' . ($this->descending ? 'DESC' : 'ASC');
        foreach ($this->further_order as $order) {
            $static = substr($order, -1) == '*';
            if ($static)
                $order = substr($order, 0, -1);
            $descending = substr($order, -1) == '-';
            if ($descending)
                $order = substr($order, 0, -1);
            if (!$static)
                $descending ^= $this->descending;
            $result .= ', ' . $order . ' ' . ($descending ? 'DESC' : 'ASC');
        }
        return $result;
    }

    function getClasses($values) {
        $result = array();
        if (isset($values['align']))
            $result[] = $values['align'];
        if (isset($values['size']))
            $result[] = $values['size'];
        return $result;
    }

    function addField($value = '') {
        if ($this->x == 0)
            $this->content .= '<tr>';
        $this->content .= '<td';
        if ($this->x == $this->force_columns - 1)
            $this->content .= ' class="last"';
        $this->content .= '>';
            $this->content .= '<div';
        if ($this->head) {
            $classes = $this->getClasses($this->columns[$this->x]);
            $this->content .= format_classes($classes);
        }
        $this->content .= '>';
        $this->content .= $value;
        $this->content .= '</div>';
        $this->content .= '</td>';
        $this->x = ($this->x + 1) % ($this->head ? count($this->columns) : $this->force_columns);
        if ($this->x == 0)
            $this->content .= '</tr>';
    }

    function getContent() {
        return $this->content . ($this->x == 0 ? '' : '</tr>');
    }

    function setPager($pager = null) {
        if (!isset($pager))
            $pager = new Pager();
        $this->pager = $pager;
        return $pager;
    }

    function setSearch($search = null) {
        if (!isset($search))
            $search = new Search(true, $this->pager);
        $this->search = $search;
        return $search;
    }

    function invert($link, $first_down) {
        global $s;
        $current = $s['h'][$this->order_index];
        if ($first_down) {
            if ($current == $link . '-')
                return $link;
            return $link . '-';
        } else {
            if ($current == $link)
                return $link . '-';
            return $link;
        }
    }

    function suffix($link) {
        global $s;
        $current = $s['h'][$this->order_index];
        if ($current == $link)
            return '+';
        if ($current == $link . '-')
            return '-';
        return '';
    }

    function canOrder($values) {
        return isset($this->order_index) && !$values['no-order'];
    }
}

?>
