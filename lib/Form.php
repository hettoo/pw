<?php

class Form {
    private $html;
    private $id;
    private $received;
    private $tabled;
    private $clear;

    function __construct($id, $clear = false) {
        $this->html = '<form action="' . this_url() . '" method="POST">';
        $this->id = $id;
        $this->tabled = false;
        $this->clear = $clear;
        if ($_POST['form_id'] == $id)
            $this->received = true;
        else
            $this->received = false;
    }

    private function clear() {
        $this->clear = true;
    }

    private function received() {
        return $this->received;
    }

    private function finishTable() {
        $result = '';
        if ($this->tabled)
            $result .= '</table>';
        return $result;
    }

    private function finishForm() {
        return '<input type="hidden" name="form_id" value="' . $this->id . '" /></form>';
    }

    private function createInput($name, $title, $type, $attributes) {
        if ($type == 'textarea') {
            $value = $attributes['value'];
            unset($attributes['value']);
            return create_element('textarea', $value, $attributes);
        } else {
            return create_element('input', '', array_merge($attributes, array('type' => $type)));
        }
    }
    
    private function fillAttributes($attributes, $name) {
        $attributes = array_merge($attributes, array('name' => $name));
        if (!$this->clear && $this->received)
            $attributes = array_merge($attributes, array('value' => $_POST[$name]));
        return $attributes;
    }

    private function tableLess($type) {
        return $type == 'hidden' || $type == 'submit');
    }

    function add($name, $title, $type, $attributes = array()) {
        $attributes = $this->fillAttributes($attributes, $name);
        if ($this->tableLess($type)) {
            $this->html .= $this->finishTable();
            $this->tabled = false;
            $this->html .= $this->createInput($name, $title, $type, $attributes);
            return;
        }
        if (!$this->tabled) {
            $this->html .= '<table>';
            $this->tabled = true;
        }
        $this->html .= '<tr>';
        $this->html .= '<td>' . $this->createInput($name, $title, $type, $attributes) . '</td>';
        $this->html .= '</tr>';
    }

    function addRaw($html) {
        $this->html .= $this->finishTable();
        $this->tabled = false;
    }

    function format() {
        return $this->html . $this->finishTable() . $this->finishForm();
    }
}

?>
