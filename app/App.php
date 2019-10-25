<?php

require('classes/DB.php');
require('Config.php');

class App {
    protected $config;

    public function __construct() {
        $config = new Config();
        $this->config = $config->getConfig();

//        $db = new DB($config['database']['host'], $config['database']['user'], $config['database']['password'], $config['database']['db']);
    }
}