<?php

namespace App\Models;

use Core\DB;

use PDO;

abstract class Model {
    protected $connection;
    protected $fillable;
    protected $table;
    protected $primary_key = 'id';

    private $belongsToMany;
    private $hasMany;
    private $relatedKey;

    public function __construct() {
        $this->connection = DB::getConnection();
        $this->getTableName();
    }

    protected function insert($fields) {
        $values = "";
        for($i = 0; $i < count($fields); $i++) {
            $fields[$i] = $this->connection->quote($fields[$i]);
            if($i == 0) $values .= $fields[$i];
            else $values .= ", " . $fields[$i];
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

        $result = $this->connection->prepare($query, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY])->execute();
        if($result) {
            $lastInsertId = $this->connection->lastInsertId();

            $newItem = $this->find($lastInsertId);
        }
        else $newItem = false;

        return $newItem;
    }

    protected function query($query) {
        $result = $this->connection->prepare($query);
        $result->execute();

        return $result;
    }

    protected function get($page = 0, $perPage = 10) {
        $start = $perPage * ($page - 1);
        if($start < 0) $start = 0;

        if($page != 0) $query = sprintf("SELECT * FROM `%s` LIMIT %d, %d", $this->table, $start, $perPage);
        else $query = sprintf("SELECT * FROM `%s`", $this->table);

        $result = $this->connection->prepare($query);
        $result->execute();

        return $result->fetchAll();
    }

    protected function find($id) {
        $query = sprintf("SELECT * FROM `%s` WHERE `%s` = :id", $this->table, $this->primary_key);
        $result = $this->connection->prepare($query);

        $result->execute([
            'id' => $id
        ]);

        return $result->fetchAll(2)[0];
    }

    protected function delete($id) {
        $query = sprintf("DELETE FROM `%s` WHERE `%s` = :id", $this->table, $this->primary_key);

        $preparedQuery = $this->connection->prepare($query);
        $result = $preparedQuery->execute([
            'id' => $id
        ]);

        return $result;
    }

    protected function update($fields, $id) {
        $statement = "";
        $i = 0;
        foreach($fields as $key => $value) {
            if(!is_null($this->fillable)) {
                if(in_array($key, $this->fillable)) {
                    if($i == 0) $statement = sprintf("SET `%s` = %s", $key, $this->connection->quote($value));
                    else $statement .= sprintf(", `%s` = %s", $key, $this->connection->quote($value));
                }
                else continue;
            }
            else {
                if($i == 0) $statement = sprintf("SET `%s` = %s", $key, $this->connection->quote($value));
                else $statement .= sprintf(", `%s` = %s", $key, $this->connection->quote($value));
            }

            $i++;
        }

        $query = sprintf("UPDATE `%s` %s WHERE `%s` = :id", $this->table, $statement, $this->primary_key, $id);

        $preparedQuery = $this->connection->prepare($query);
        $result = $preparedQuery->execute([
            'id' => $id
        ]);

        if($result) {
            $newItem = $this->find($id);
        }
        else $newItem = false;

        return $newItem;
    }

    private function getTableName() {
        if(is_null($this->table)) {
            $class_name = get_class($this);
            $this->table = end(explode('\\', $class_name));
            $this->table = strtolower($this->table);
            $this->table .= 's';
        }

        return $this->table;
    }
}