<?php

namespace App\Controllers;

use App\Models\Book;
use Core\Request;

class BookController extends Controller {
    public static function getBooks() {
        $book = new Book();
        $books = $book->getBooks();

        return $books;
    }

    public static function getBook($id) {
        $book = new Book();

        return $book->getBook($id);
    }

    public static function addBook(Request $request) {
        $request = $request->getParams();
        $book = new Book();

        $newBook = $book->addBook($request);

        return $newBook;
    }

    public static function deleteBook($id) {
        $book = new Book();
        $result = $book->deleteBook($id);

        return $result;
    }

    public static function updateBook($id, $bookFields) {
        $book = new Book();

        $result = $book->updateBook($id, $bookFields);

        return $result;
    }
}