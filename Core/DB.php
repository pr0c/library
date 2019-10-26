<?php

namespace Core;

use App\App;
use PDO;

class DB {
    private static $connection = null;

    public static function getConnection() {
        if(is_null(self::$connection)) {
            try {
                self::$connection = new PDO(sprintf('mysql:host=%s;dbname=%s', App::$config['database']['host'], App::$config['database']['db']), App::$config['database']['user'], App::$config['database']['password']);
            }
            catch(\Exception $exception) {
                die($exception);
            }
        }

        return self::$connection;
    }
}