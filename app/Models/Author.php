<?php

namespace App\Models;

class Author extends Model {
    protected $fillable = ['name'];

    public function addAuthor(array $fields) {
        return $this->insert($fields);
    }

    public function getAuthors() {
        return $this->get();
    }

    public function getAuthor($id) {
        return $this->find($id);
    }

    public function deleteAuthor($id) {
        return $this->delete($id);
    }

    public function updateAuthor($id, $author) {
        $author = json_decode($author['author']);
        return $this->update($author, $id);
    }

    public function getBooks($id) {
        /*$books = $this->belongsToMany(Book::class, $id, 'authors_books');

        return $books;*/
    }
}