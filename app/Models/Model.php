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

    public function __construct() {
        $this->connection = DB::getConnection();
        $this->getTableName();
    }

    protected function insert($fields) {
        $values = "";

        $i = 0;
        foreach($fields as $key => $value) {
            $value = $this->connection->quote($value);
            if($i == 0) $values .= $value;
            else $values .= ", " . $value;

            $i++;
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

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function find($id) {
        $query = sprintf("SELECT * FROM `%s` WHERE `%s` = :id", $this->table, $this->primary_key);
        $result = $this->connection->prepare($query);

        $result->execute([
            'id' => $id
        ]);

        return $result->fetchAll(PDO::FETCH_ASSOC)[0];
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

    protected function belongsToMany($class, $recordId, $related_table, $related_key = null, $reverse_key = null) {
        $class = new $class();

        if(is_null($related_table)) {
            $related_table = sprintf('%s_%s', $class->table, $this->table);
        }

        if(is_null($related_key)) {
            $related_key = sprintf('%s_id', rtrim($this->table, 's'));
        }

        if(is_null($reverse_key)) {
            $reverse_key = sprintf('%s_id', rtrim($class->table, 's'));
        }

        $result = $this->query(sprintf("SELECT * FROM `%s` WHERE `%s` = %d", $related_table, $related_key, $recordId));
        $result = $result->fetchAll(PDO::FETCH_ASSOC);

        $records = [];
        foreach($result as $record) {
            $tmp = $this->query(sprintf("SELECT * FROM `%s` WHERE `%s` = %d", $class->table, $class->primary_key, $record[$reverse_key]));
            $records[] = $tmp->fetchAll(PDO::FETCH_ASSOC)[0];
        }

        return $records;
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