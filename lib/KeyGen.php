<?php

class KeyGen {
    private $chars;

    function __construct($chars = '1234567890qwertyuiopasdfghjklzxcvbnm') {
        $this->chars = $chars;
        $this->length = strlen($this->chars);
    }

    function generate($size) {
        $key = '';
        for ($i = 0; $i < $size; $i++)
            $key .= $this->chars[rand() % $this->length];
        return $key;
    }
}

?>
