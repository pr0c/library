<?php

namespace App\Models;

use App\Models\Author;

class Book extends Model {
    protected $fillable = ['title', 'description'];

    public function addBook($book) {
        $book = json_decode($book['book'], true);
        if(isset($book['authors'])) {
            $authors = $book['authors'];
            unset($book['authors']);
        }
        else $authors = false;

        $result = $this->insert($book);
        if($result && $authors) {
            foreach($authors as $author) {
                $this->query(sprintf("INSERT INTO `authors_books` (`book_id`, `author_id`) VALUES (%d, %d)", $result['id'], $author['id']));
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
        $book = json_decode($book['book'], true);
        if(isset($book['authors'])) {
            $authors = $book['authors'];
            unset($book['authors']);
        }
        else $authors = false;

        $newBook = $this->update($book, $id);

        if($newBook) {
            $this->query(sprintf("DELETE FROM `authors_books` WHERE `book_id` = %d", $id));
            if($authors) {
                foreach($authors as $author) {
                    $this->query(sprintf("INSERT INTO `authors_books` (`book_id`, `author_id`) VALUES (%d, %d)", $id, $author['id']));
                }
            }
        }
        return $newBook;
    }

    public function getAuthors($id) {
        $authors = $this->belongsToMany(Author::class, $id, 'authors_books');

        return $authors;
    }
}