<?php

namespace App\Controller;

use App\Models\Book;
use Core\Request;

class BookController extends Controller {
    public static function getBooks(Request $request) {
        $request = $request->getParams();
        $book = new Book();

        $page = $request['page'] ?: 0;
        $perPage = $request['perPage'] ?: 10;

        $books = $book->getBooks($page, $perPage);

        echo json_encode($books);
    }

    public static function getBook(Request $request) {
        $request = $request->getParams();
        $book = new Book();

        if(isset($request['id'])) {
            echo json_encode($book->getBook($request['id']));
        }
    }
}