<?php

import_lib('Form');

class Search {
    private $index;
    private $form;
    private $pager;

    function __construct($redirect = true, $pager = null) {
        global $s;
        $this->index = find_index('search');
        $this->form = new Form('search', isset($pager) && $pager->drawable() ? 'left' : null);
        $this->form->setData(array('name' => unescape_url($s['h'][$this->index])));
        $this->form->add('Name', 'text', 'name', false, array('class' => 'search'));
        $this->form->add('Search', 'submit');
        if ($redirect)
            $this->redirect();
        $this->pager = $pager;
    }

    function redirect() {
        if ($this->form->received())
            redirect(url(escape_url($this->form->get('name')), $this->index, false));
    }

    function get() {
        global $s;
        return simplify(unescape_url($s['h'][$this->index]));
    }

    function getLike() {
        return " LIKE '%" . secure($this->get()) . "%'";
    }

    private function setForm() {
        if (isset($this->form))
            return;
    }

    function getForm() {
        return $this->form;
    }

    function getPager() {
        return $this->pager;
    }
}

?>
