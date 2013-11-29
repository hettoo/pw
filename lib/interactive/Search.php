<?php

import_lib('interactive/Form');
import_lib('utils/MultiFormat');

class Search extends MultiFormat {
    private $index;
    private $form;
    private $pager;

    function __construct($redirect = true, $pager = null) {
        parent::__construct('search');
        global $s;
        $this->index = find_index('search');
        $this->form = new Form('search', isset($pager) && $pager->drawable() ? 'left' : null);
        $this->form->setData(array('name' => unescape_url($s['h'][$this->index])));
        $this->form->add('Name', 'text', 'name', false, array('class' => 'search'));
        $this->form->add('Search', 'submit');
        $this->pager = $pager;
        if ($redirect)
            $this->redirect();
    }

    function redirect() {
        global $s;
        if ($this->form->received()) {
            if (isset($this->pager))
                $s['h'][$this->pager->getIndex()] = 1;
            redirect(url(escape_url($this->form->get('name')), $this->index, false));
        }
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
