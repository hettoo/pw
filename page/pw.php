<p>
PW needs to know about its database.
</p>

<?php
if (!isset($_POST['key']) || $_POST['key'] != $s['key']) {
?>

<p>
To be able to set this up, enter the code found in the key file in the setup folder of the project in the box below.
</p>

<form action="<?= this_url(); ?>" method="POST">
    <input type="text" name="key" />
    <input type="submit" name="submit" value="Submit">
</form>

<?php
} elseif (!isset($_POST['host'])) {
?>

<form action="<?= this_url(); ?>" method="POST">
<table>
    <tr><td>Host</td><td><input type="text" name="host" /></td></tr>
    <tr><td>Database</td><td><input type="text" name="database" /></td></tr>
    <tr><td>User</td><td><input type="text" name="user" /></td></tr>
    <tr><td>Password</td><td><input type="password" name="password" /></td></tr>
</table>
<input type="hidden" name="key" value="<?= $s['key']; ?>" />
<input type="submit" name="submit" value="Submit" />
</form>

<?php
} else {
    import_lib('setup');
    $fp = fopen('setup', 'w');
    fwrite($fp, $_POST['host']);
    fwrite($fp, "\n");
    fwrite($fp, $_POST['database']);
    fwrite($fp, "\n");
    fwrite($fp, $_POST['user']);
    fwrite($fp, "\n");
    fwrite($fp, $_POST['password']);
    fwrite($fp, "\n");
?>

Setup saved!

<?php
}
?>
