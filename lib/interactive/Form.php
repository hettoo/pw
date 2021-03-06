<?php

import_lib('core/session');
import_lib('external/simple-php-captcha/simple-php-captcha');
import_lib('utils/MultiFormat');

class Form extends MultiFormat {
    private $id;
    private $class;
    private $elements;
    private $names;
    private $errors;
    private $errored;
    private $received;
    private $clear;
    private $data;

    function __construct($id = 'form', $class = null) {
        parent::__construct('form');
        $this->id = $id;
        $this->class = $class;
        $this->elements = array();
        $this->names = array();
        $this->errors = array();
        $this->errored = array();
        $this->data = array();
        $this->clear = false;
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

    function addError($error, $name = null) {
        if (!$this->received)
            return;
        $this->errors[] = $error;
        if (isset($name))
            $this->errored[$name] = true;
    }

    function check() {
        if (!$this->received)
            return true;
        $result = true;
        foreach ($this->names as $name => $value) {
            if (!empty($name) && $value == 2 && $this->get($name) == '')
                $this->addError(ucfirst($name) . ' can not be empty.', $name);
        }
        return empty($this->errors);
    }

    function getErrors() {
        return $this->errors;
    }

    function getElements() {
        return $this->elements;
    }

    private function tableLess($type) {
        return $type == 'hidden' || $type == 'submit';
    }

    private function createInput($name, $title, $type, $attributes) {
        if (!$this->tableLess($type) && (!isset($attributes['class']) || empty($attributes['class'])))
            $attributes['class'] = 'normal';
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
        if (!$this->names[$name])
            return null;
        return $_POST[$this->id . '_' . $name];
    }

    function getFile($name) {
        if (!$this->names[$name])
            return null;
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

    private function prepare($title, $type, $name, $attributes) {
        $clear = isset($attributes['clear']) ? $attributes['clear'] : false;
        unset($attributes['clear']);
        if (isset($name))
            $attributes = $this->fillAttributes($attributes, $name, $type != 'file' && !$clear);
        return $this->createInput($name, $title, $type, $attributes);
    }

    function add($title, $type, $name = null, $obligatory = true, $attributes = array()) {
        $visual_title = $title;
        if ($this->tableLess($type)) {
            $visual_title = '';
            $obligatory = false;
        }
        $this->names[$name] = $obligatory ? 2 : 1;
        $this->elements[] = array($visual_title, $obligatory, $this->prepare($title, $type, $name, $attributes));
    }

    function addRaw($html, $title = null, $obligatory = null) {
        $this->elements[] = array($title, $obligatory, $html);
    }

    function addCaptcha() {
        $_SESSION['captcha_' . $this->id] = simple_php_captcha();
        $this->addRaw('<img src="' . url('captcha-image') . '" alt="CAPTCHA security code">', '', false);
        $this->add('Captcha', 'text', 'captcha', true, array('clear' => 1));
    }
}

?>
