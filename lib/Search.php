<?php

class Search {
    private $index;

    function __construct() {
        $this->index = find_index('search');
    }

    function redirect($pager) {
        global $hierarchy;
        if ($_POST['submit']) {
            $hierarchy[$pager->getIndex()] = '1';
            redirect(url(escape_url($_POST['name']), $this->index, false));
        }
    }

    function get() {
        global $hierarchy;
        return secure(simplify(unescape_url($hierarchy[$this->index])));
    }

    function format($pager = null) {
        global $hierarchy;
        $result = '<p>';
        $result .= '<form action="' . this_url() . '" method="POST"' . ($pager && $pager->drawable() ? ' class="left"' : '' ) . '>';
        $result .= '<input type="text" name="name" value="' . secure(unescape_url($hierarchy[$this->index]), 'html') . '" />';
        $result .= '<input type="submit" name="submit" value="Search">';
        $result .= '</form>';
        if ($pager)
            $result .= $pager->format();
        $result .= '</p>';
        return $result;
    }
}

?>
