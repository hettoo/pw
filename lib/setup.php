<?php

$result = $s['db']->query("CREATE TABLE IF NOT EXISTS `config` (`key` VARCHAR(32) PRIMARY KEY, `value` TEXT)");

?>
