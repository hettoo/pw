<?php

class Form {
    private $html;
    private $id;
    private $inline;
    private $received;
    private $tabled;
    private $clear;
    private $data;

    function __construct($id = 'form', $inline = false, $class = null) {
        $this->html = '<form action="' . this_url() . '" method="POST" enctype="multipart/form-data"' . (isset($class) ? ' class="' . $class . '"' : '') . '>';
        $this->id = $id;
        $this->inline = $inline;
        $this->data = array();
        $this->clear = false;
        $this->tabled = false;
        if ($_POST['_form_id'] == $id)
            $this->received = true;
        else
            $this->received = false;
    }

    function setData($data) {
        $this->data = $data;
    }

    function setClear($clear) {
        $this->clear = $clear;
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
            return create_element($type, secure($value, 'html'), $attributes);
        } else if ($type == 'select') {
            $options = $attributes['options'];
            unset($attributes['options']);
            $active = $attributes['value'];
            unset($attributes['value']);
            $i = 0;
            foreach ($options as $option)
                $html .= '<option value="' . $i . '" ' . ($i++ == $active ? ' selected="selected"' : '') . '>' . $option . '</option>';
            return create_element($type, $html, $attributes);
        } elseif ($type == 'checkbox') {
            $attributes['value'] = '1';
            $attributes['type'] = $type;
        } elseif ($this->tableLess($type)) {
            $attributes['value'] = $title;
            $attributes['type'] = $type;
        } else {
            $attributes['type'] = $type;
        }
        return create_element('input', '', $attributes);
    }

    function get($name) {
        return $_POST[$this->id . '_' . $name];
    }

    function getFile($name) {
        return $_FILES[$this->id . '_' . $name];
    }

    private function fillAttributes($attributes, $name, $set_value) {
        $attributes['name'] = $this->id . '_' . $name;
        if (!$this->clear && $set_value)
            $attributes['value'] = $this->received && !is_null($this->get($name)) ? $this->get($name) : $this->data[$name];
        return $attributes;
    }

    function add($title, $type, $name = null, $obligatory = true, $attributes = array()) {
        if (isset($name))
            $attributes = $this->fillAttributes($attributes, $name, $type != 'file');
        if ($this->inline || $this->tableLess($type)) {
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
        $this->html .= '<td>' . $title . ($obligatory ? ' <span class="obligatory">*</span>' : '') . '</td><td>' . $this->createInput($name, $title, $type, $attributes) . '</td>';
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
