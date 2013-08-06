<?php

class Search {
    private $index;

    function __construct($redirect = true) {
        $this->index = find_index('search');
        if ($redirect)
            $this->redirect();
    }

    function redirect() {
        global $hierarchy;
        if ($_POST['submit'])
            redirect(url(escape_url($_POST['name']), $this->index, false));
    }

    function get() {
        global $hierarchy;
        return simplify(unescape_url($hierarchy[$this->index]));
    }

    function getLike() {
        return " LIKE '%" . secure($this->get()) . "%'";
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
