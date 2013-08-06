<?php

import_lib('Account');

$register = false;
$index = find_index('mode');
if (isset($index))
    $register = $hierarchy[$index] == 'register';

$s['hide'] = true;

function admin_registration($form) {
    $key = file_get_contents('setup/key');
    if (empty($key))
        return 'No key file found.';
    if ($form->get('key') != $key)
        return 'Invalid key.';
    $errors = array();
    $name = $form->get('name');
    $password = $form->get('password');
    $level = $form->get('level');
    $email = $form->get('email');
    if (strlen($name) == 0)
        $errors[] = 'The name field is required.';
    if (strlen($password) == 0)
        $errors[] = 'The password field is required.';
    if ($password != $form->get('confirmation'))
        $errors[] = 'The passwords do not match.';
    if (!is_numeric($level))
        $errors[] = 'Level must be a number.';
    if (strlen($email) == 0)
        $errors[] = 'The e-mail field is required.';
    if (empty($errors)) {
        $name = secure($name);
        $password = secure($password);
        $email = secure($email);
        query("INSERT INTO `admin` SET `level`=$level, `name`='$name', `password`=MD5('$password'), `email`='$email'");
    }
    return $errors;
}

$account = new Account('admin');
$extra_fields = null;
if ($register) {
    $extra_fields = array(
        array('Confirmation', 'password', 'confirmation'),
        array('Level', 'text', 'level'),
        array('E-mail', 'text', 'email'),
        array('Key', 'text', 'key'),
        admin_registration
    );
}
$result = $account->login($extra_fields);

function fail() {
    global $s;
    $s['admin'] = null;
    $s['head'] = 'Admin' . ($register ? ' registration' : '');
    $s['description'] = 'Administration area.';
}

if (!$result) {
    fail();
} else {
    $s['admin'] = $account;
    $s['modules'] = array();
    $s['module_levels'] = array();
    $s['submenu'] = array(array('', 'Overview'));

    function add_module($name, $level, $items) {
        global $s;
        $nice = nicen($name);
        if ($s['admin']->permits($level)) {
            $s['modules'][] = array($name, $items);
            $s['submenu'][] = array($nice, $name);
        }
        $s['module_levels'][$nice] = $level;
    }

    import_lib('admin/init');

    $s['submenu'] = create_menu(1, $s['submenu']);

    $module = $hierarchy[1];
    $level = $s['module_levels'][$module];
    if (isset($level) && !$s['admin']->permits($level)) {
        section('single', 'You do not have permission to be here.');
        fail();
    }
}

function list_modules() {
    global $s, $hierarchy;
    section('modules', array($hierarchy[0], $s['modules']));
}

?>
