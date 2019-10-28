<?php

namespace App;

use App\Controllers\BookController;
use Core\Request;
use Core\Router;

$router = new Router(new Request);

$router->get('/', function() {
    echo 'Home page';
});
