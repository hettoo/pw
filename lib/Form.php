<?php

import_lib('core/session');
import_lib('external/simple-php-captcha/simple-php-captcha');

class Form {
    private $id;
    private $class;
    private $elements;
    private $inline;
    private $received;
    private $tabled;
    private $clear;
    private $data;

    function __construct($id = 'form', $inline = false, $class = null) {
        $this->elements = array();
        $this->id = $id;
        $this->class = $class;
        $this->inline = $inline;
        $this->data = array();
        $this->clear = false;
        $this->tabled = false;
        if ($_POST['_form_id'] == $id)
            $this->received = true;
        else
            $this->received = false;
    }

    function getId() {
        return $this->id;
    }

    function getClass() {
        return $this->class;
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

    private function startTable() {
        if (!$this->tabled) {
            $this->elements[] = '<table>';
            $this->tabled = true;
        }
    }

    private function finishTable() {
        if ($this->tabled) {
            $this->elements[] = '</table>';
            $this->tabled = false;
        }
    }

    function getElements() {
        $this->finishTable();
        return $this->elements;
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
            $content = '';
            $i = 0;
            foreach ($options as $option)
                $content .= '<option value="' . $i . '" ' . ($i++ == $active ? ' selected="selected"' : '') . '>' . $option . '</option>';
            return create_element($type, $content, $attributes);
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

    function checkCaptcha() {
        $code = $_SESSION['captcha_' . $this->id]['code'];
        if (!isset($code))
            return false;
        return $code == $this->get('captcha');
    }

    private function fillAttributes($attributes, $name, $set_value) {
        $attributes['name'] = $this->id . '_' . $name;
        if (!$this->clear && $set_value)
            $attributes['value'] = $this->received && !is_null($this->get($name)) ? $this->get($name) : $this->data[$name];
        return $attributes;
    }

    function add($title, $type, $name = null, $obligatory = true, $attributes = array()) {
        $clear = $attributes['clear'];
        unset($attributes['clear']);
        if (isset($name))
            $attributes = $this->fillAttributes($attributes, $name, $type != 'file' && !$clear);
        if ($this->inline || $this->tableLess($type)) {
            $this->finishTable();
            $this->elements[] = $this->createInput($name, $title, $type, $attributes);
            return;
        }
        $this->startTable();
        $content = '<tr>';
        $content .= '<td>' . $title . ($obligatory ? ' <span class="obligatory">*</span>' : '') . '</td><td>' . $this->createInput($name, $title, $type, $attributes) . '</td>';
        $content .= '</tr>';
        $this->elements[] = $content;
    }

    function addCaptcha() {
        $_SESSION['captcha_' . $this->id] = captcha();
        $this->startTable();
        $this->elements[] = '<tr><td></td><td><img src="' . url('captcha-image') . '" alt="CAPTCHA security code" /></td></tr>';
        $this->add('Captcha', 'text', 'captcha', true, array('clear' => 1));
    }

    function addRaw($html) {
        $this->finishTable();
        $this->elements[] = $html;
    }
}

?>
