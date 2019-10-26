<?php

namespace App;

use Core\DB;
use App\Config;

class App {
    public static $config;

    public function __construct() {
        $config = new Config();
        self::$config = $config->getConfig();

//        $db = new DB($config['database']['host'], $config['database']['user'], $config['database']['password'], $config['database']['db']);
    }
}