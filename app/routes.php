<?php

namespace App;

use App\Controller\BookController;
use Core\Request;
use Core\Router;

$router = new Router(new Request);

$router->get('/', function() {
    echo 'Home page';
});

$router->post('/book', function($request) {
    BookController::getBook($request);
});

