<?php

import_lib('utils/Account');

$register = false;
$index = find_index('mode');
if (isset($index))
    $register = $s['h'][$index] == 'register';

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
        query("INSERT INTO `" . prefix('admin') . "` SET `level`=$level, `name`='$name', `password`=MD5('$password'), `email`='$email'");
    }
    return $errors;
}

$account = new Account(prefix('admin'));
$extra_fields = null;
if ($register) {
    $extra_fields = array(
        array('Confirmation', 'password', 'confirmation'),
        array('E-mail', 'text', 'email')
    );
    if (!$s['admin_register']) {
        $extra_fields[] = array('Level', 'text', 'level');
        $extra_fields[] = array('Key', 'text', 'key');
    }
    $extra_fields[] = admin_registration;
}
$result = $account->login($extra_fields, $register && $s['admin_register']);

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

    function add_module($name, $level, $items = array(), $specifics = array()) {
        global $s;
        $nice = nicen($name);
        if ($s['admin']->permits($level)) {
            $s['modules'][] = array($name, $items, $specifics);
            $s['submenu'][] = array($nice, $name);
        }
        $s['module_levels'][$nice] = $level;
    }

    import_lib('admin/init');

    $s['submenu'] = create_menu(1, $s['submenu']);

    $module = $s['h'][1];
    $level = $s['module_levels'][$module];
    if (isset($level) && !$s['admin']->permits($level)) {
        section('error', 'You do not have permission to be here.');
        fail();
    }
}

function list_modules() {
    global $s;
    wrap_section('modules', 'modules', array($s['h'][0], $s['modules']));
}

function admin_actions($index, $id = 0, $short = false) {
    global $s;
    foreach ($s['modules'] as $module) {
        if (nicen($module[0]) == $s['h'][$index - 1]) {
            $result = array();
            if (!$short) {
                $items = $module[1];
                foreach ($items as $item) {
                    if (is_array($item))
                        $name = $item[0];
                    else
                        $name = $item;
                    if ($id || $name != $s['h'][$index])
                        $result[] = $item;
                }
            }
            $specifics = $module[2];
            if ($id) {
                foreach ($specifics as $item) {
                    if (is_array($item)) {
                        $name = $item[0];
                        $new = $item;
                        if ($short)
                            $new[1] = $new[0];
                    } else {
                        $name = $item;
                        $new = array($item, $item);
                    }
                    if ($name != $s['h'][$index]) {
                        $new[0] = $name . '/' . $id;
                        $result[] = $new;
                    }
                }
            }
            if ($short)
                return action_list($index, $result, ' ');
            return action_list($index, $result);
        }
    }
    return '';
}

function admin_upper_urls($urls) {
    wrap_section('upper-urls', 'clean', $urls);
}

function admin_lower_urls($urls) {
    wrap_section('lower-urls', 'clean', $urls);
}

?>
