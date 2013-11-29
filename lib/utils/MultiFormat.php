<?php

class MultiFormat {
    private $id;

    function __construct($id) {
        $this->id = $id;
    }

    function show($type = 'default') {
        section($this->id . '/' . $type, $this);
    }
}

?>
