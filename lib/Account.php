<?php

import_lib('Form');

class Account {
    private $table;
    private $id;
    private $level;
    private $name;

    function __construct($table = null) {
        if (!isset($table))
            $table = prefix('users');
        $this->id = 0;
        $this->level = 0;
        $this->table = $table;
    }

    function loggedIn() {
        return $this->id != 0;
    }

    function login($registration = null, $captcha) {
        if ($this->restore())
            return true;
        $form = new Form($this->table);
        $function = null;
        if (!is_null($registration)) {
            $function = array_pop($registration);
        }
        if ($form->received()) {
            $login = true;
            if ($function != null) {
                $login = false;
                $result = $function($form);
                if (is_array($result) && !empty($result)) {
                    section('error', $result);
                } elseif (is_array($result) || is_null($result)) {
                    $login = true;
                } else {
                    section('error', $result);
                }
            }
            if ($login) {
                $name = $form->get('name');
                $password = $form->get('password');
                $this->name = $name;
                $result = query("SELECT `id`, `level` FROM `$this->table` WHERE `name`='$name' AND `password`=MD5('$password')");
                if ($row = $result->fetch_array()) {
                    $this->id = $row['id'];
                    $this->level = $row['level'];
                    $_SESSION[$this->table] = $row['id'];
                    return true;
                }
                section('error', 'Incorrect name / password combination.');
            }
        }
        $form->add('Name', 'text', 'name');
        $form->add('Password', 'password', 'password');
        if (!is_null($registration)) {
            foreach ($registration as $arguments)
                call_user_func_array(array($form, 'add'), $arguments);
        }
        if ($captcha)
            $form->addCaptcha();
        $form->add('Submit', 'submit');
        section('form', $form);
        return false;
    }

    function restore() {
        $id = $_SESSION[$this->table];
        if (!$id)
            return false;
        $this->id = $id;
        $result = query("SELECT `name`, `level` FROM `$this->table` WHERE `id`=$id");
        if ($row = $result->fetch_array()) {
            $this->name = $row['name'];
            $this->level = $row['level'];
            return true;
        }
        return false;
    }

    function logout() {
        unset($_SESSION[$this->table]);
    }

    function permits($level) {
        return $this->level >= $level;
    }
}

?>
