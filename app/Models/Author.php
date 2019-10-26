<?php

namespace App\Models;

class Author extends Model {
    protected $fillable = ['name'];

    public function addAuthor(array $fields) {
        $result = $this->insert($fields);

        return $result;
    }

    public function getAuthors() {
        $authors = $this->get("SELECT * FROM authors");

        return $authors->fetchAll(2);
    }
}