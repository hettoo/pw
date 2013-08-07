<?php

import_lib('Form');

class Search {
    private $index;
    private $form;
    private $pager;

    function __construct($redirect = true, $pager = null) {
        $this->index = find_index('search');
        if ($redirect)
            $this->redirect();
        $this->form = null;
        $this->pager = $pager;
    }

    function redirect() {
        $form = new Form('search');
        if ($form->received())
            redirect(url(escape_url($form->get('name')), $this->index, false));
    }

    function get() {
        global $hierarchy;
        return simplify(unescape_url($hierarchy[$this->index]));
    }

    function getLike() {
        return " LIKE '%" . secure($this->get()) . "%'";
    }

    private function setForm() {
        global $hierarchy;
        if (isset($this->form))
            return;
        $this->form = new Form('search', true, isset($this->pager) && $this->pager->drawable() ? 'left' : null);
        $this->form->setData(array('name' => unescape_url($hierarchy[$this->index])));
        $this->form->add('Name', 'text', 'name');
        $this->form->add('Search', 'submit');
    }

    function format() {
        $result = '<p>';
        $this->setForm();
        $result .= $this->form->format();
        if ($this->pager)
            $result .= $this->pager->format();
        $result .= '</p>';
        return $result;
    }
}

?>
