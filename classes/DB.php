<?php
require_once('app/App.php');

class DB {
    private static $connection = null;

    public function __construct($host, $user, $password, $db_name) {
        if(is_null(self::$connection)) {
            try {
                self::$connection = new PDO(sprintf('mysql:host=%s;dbname=%s', $host, $db_name), $user, $password);
            } catch (Exception $exception) {
                die('Database error');
            }
        }
        return self::$connection;
    }
}