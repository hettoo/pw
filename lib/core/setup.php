<?php

query("CREATE TABLE IF NOT EXISTS `config` (`key` VARCHAR(32) NOT NULL PRIMARY KEY, `value` TEXT)");
query("CREATE TABLE IF NOT EXISTS `page` (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY)");
query("CREATE TABLE IF NOT EXISTS `content` (`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, `page` INT NOT NULL, `content` TEXT)");

?>
