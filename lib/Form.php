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
        if ($_POST['_form_id'] == $id)
            $this->received = true;
        else
            $this->received = false;
    }

    private function clear() {
        $this->clear = true;
    }

    function received() {
        return $this->received;
    }

    private function finishTable() {
        $result = '';
        if ($this->tabled)
            $result .= '</table>';
        return $result;
    }

    private function finishForm() {
        return '<input type="hidden" name="_form_id" value="' . $this->id . '" /></form>';
    }

    private function tableLess($type) {
        return $type == 'hidden' || $type == 'submit';
    }

    private function createInput($name, $title, $type, $attributes) {
        if ($type == 'textarea') {
            $value = $attributes['value'];
            unset($attributes['value']);
            return create_element('textarea', $value, $attributes);
        } elseif ($this->tableLess($type)) {
            return create_element('input', '', array_merge($attributes, array('value' => $title, 'type' => $type)));
        } else {
            return create_element('input', '', array_merge($attributes, array('type' => $type)));
        }
    }
    
    private function fillAttributes($attributes, $name) {
        $attributes = array_merge($attributes, array('name' => $this->id . '_' . $name));
        if (!$this->clear && $this->received)
            $attributes = array_merge($attributes, array('value' => $_POST[$this->id . '_' . $name]));
        return $attributes;
    }

    function get($name) {
        return $_POST[$this->id . '_' . $name];
    }

    function add($title, $type, $name = null, $attributes = array()) {
        if (isset($name))
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
        $this->html .= '<td>' . $title . '</td><td>' . $this->createInput($name, $title, $type, $attributes) . '</td>';
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
