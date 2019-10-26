<?php

namespace App\Models;

use Core\DB;

use PDO;

abstract class Model {
    protected $connection;
    protected $fillable;
    protected $table;

    public function __construct() {
        $this->connection = DB::getConnection();
    }

    protected function insert($fields) {
        $values = "";
        for($i = 0; $i < count($fields); $i++) {
            if(is_numeric($fields[$i])) {
                if($i == 0) $values .= $fields[$i];
                else $values .= ", " . $fields[$i];
            }
            else {
                if($i == 0) $values .= "'" . $fields[$i] . "'";
                else $values .= ", '" . $fields[$i] . "'";
            }
        }

        if(is_null($this->table)) {
            $class_name = get_class($this);
            $this->table = end(explode('\\', $class_name));
            $this->table = strtolower($this->table);
            $this->table .= 's';
        }

        if(!is_null($this->fillable)) {
            $rows = "";
            for($i = 0; $i < count($this->fillable); $i++) {
                if($i == 0) $rows .= "`" . $this->fillable[$i] . "`";
                else $rows .= ", `" . $this->fillable[$i] . "`";
            }

            $query = sprintf("INSERT INTO `%s` (%s) VALUES(%s)", $this->table, $rows, $values);
        }
        else $query = sprintf("INSERT INTO `%s` VALUES(%s)", $this->table, $values);

        $result = $this->connection->prepare($query, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
        $result->execute();
    }

    protected function get($query) {
        $result = $this->connection->prepare($query);
        $result->execute();

        return $result;
    }
}