<?php

namespace App;

class Config {
    private $data;

    public function __construct() {
        $this->data = require 'config/config.php';

        return $this->data;
    }

    public function getConfig() {
        if($this->data !== null) return $this->data;
    }
}