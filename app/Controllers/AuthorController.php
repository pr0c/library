<?php

namespace App\Controllers;

use App\Models\Author;
use Core\Request;

class AuthorController extends Controller {
    public static function getAuthors() {
        $author = new Author();

        $authors = $author->getAuthors();

        return $authors;
    }

    public static function getAuthor($id) {
        $author = new Author();

        return $author->getAuthor($id);
    }

    public static function addAuthor(Request $request) {
        $request = $request->getParams();
        $author = new Author();

        $newAuthor = $author->addAuthor($request);

        return $newAuthor;
    }

    public static function updateAuthor($id, $authorFields) {
        $author = new Author();

        return $author->updateAuthor($id, $authorFields);
    }

    public static function deleteAuthor($id) {
        $author = new Author();

        return $author->deleteAuthor($id);
    }
}