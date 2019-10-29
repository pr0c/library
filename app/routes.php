<?php

namespace App;

use App\Controllers\AuthorController;
use App\Controllers\BookController;
use Core\Request;
use Core\Router;

$router = new Router(new Request);

$router->get('/', function() {
    echo 'Home page';
});

//Books
$router->get('/book/{id}', function($request) {
    $request = $request->getParams();
    $book = BookController::getBook($request['id']);

    echo json_encode($book);
});

$router->get('/book', function() {
    $books = BookController::getBooks();

    echo json_encode($books);
});

$router->post('/book/{id}', function($request) {
    $request = $request->getParams();
    $book = BookController::updateBook($request['id'], $request);

    echo json_encode($book);
});

$router->delete('/book/{id}', function($request) {
    $request = $request->getParams();
    $result = BookController::deleteBook($request['id']);

    echo json_encode($result);
});

$router->post('/book', function($request) {
    $book = BookController::addBook($request);

    echo json_encode($book);
});

//Authors
$router->get('/author/{id}', function($request) {
    $request = $request->getParams();
    $authors = AuthorController::getAuthor($request['id']);

    echo json_encode($authors);
});

$router->get('/author', function() {
    $authors = AuthorController::getAuthors() ;

    echo json_encode($authors);
});

$router->post('/author/{id}', function($request) {
    $request = $request->getParams();
    $author = AuthorController::updateAuthor($request['id'], $request);

    echo json_encode($author);
});

$router->delete('/author/{id}', function($request) {
    $request = $request->getParams();
    $result = AuthorController::deleteAuthor($request['id']);

    echo json_encode($result);
});

$router->post('/author', function($request) {
    $author = AuthorController::addAuthor($request);

    echo json_encode($author);
});
