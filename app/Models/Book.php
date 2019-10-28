<?php

namespace App\Models;

use App\Models\Author;

class Book extends Model {
    protected $fillable = ['title', 'description'];

    public function addBook($book) {
        $result = $this->insert($book);
        if(isset($book['authors'])) {
            foreach($book['authors'] as $author) {
                $this->query(sprintf("INSERT INTO `authors_books` (`book_id`, `author_id`) VALUES (%d, %d)", $book['id'], $author['id']));
            }
        }
        return $result;
    }

    public function getBooks() {
        return $this->get();
    }

    public function getBook($id) {
        $book = $this->find($id);
        $book['authors'] = $this->getAuthors($id);
        return $book;
    }

    public function deleteBook($id) {
        $result = $this->delete($id);

        return $result;
    }

    public function updateBook($id, $book) {
        $newBook = $this->update($book, $id);

        return $newBook;
    }

    public function getAuthors($id) {
        $authors = $this->belongsToMany(Author::class, $id);

        return $authors;
    }
}