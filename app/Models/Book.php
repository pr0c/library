<?php

namespace App\Models;

class Book extends Model {
    protected $fillable = ['title', 'description', 'author_id'];

    public function addBook($book) {
        $result = $this->insert($book);

        return $result;
    }

    public function getBooks() {
        $books = $this->get("SELECT * FROM books");

        return $books->fetchAll(2);
    }
}