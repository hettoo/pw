<?php

import_lib('core/session');
import_lib('Form');

class Account {
    private $table;
    private $id;
    private $level;
    private $name;

    function __construct($table = 'users') {
        $this->id = 0;
        $this->level = 0;
        $this->table = $table;
    }

    function login() {
        if ($this->restore())
            return true;
        $form = new Form($this->table);
        if ($form->received()) {
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
            section('single', 'Incorrect name / password combination.');
        }
        $form->add('Name', 'text', 'name');
        $form->add('Password', 'password', 'password');
        $form->add('Submit', 'submit');
        section('single', $form->format());
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
}

?>
