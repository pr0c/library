<?php

namespace App\Models;

use App\Models\Author;

class Book extends Model {
    protected $fillable = ['title', 'description', 'author_id'];

    public function addBook($book) {
        $result = $this->insert($book);

        return $result;
    }

    public function getBooks($page = -1, $perPage = 10) {
        $books = $this->get($page, $perPage);

        return $books;
    }

    public function getBook($id) {
        $book = $this->find($id);

        return $book;
    }

    public function deleteBook($id) {
        $result = $this->delete($id);

        return $result;
    }

    public function updateBook($book, $id) {
        $newBook = $this->update($book, $id);

        return $newBook;
    }
}