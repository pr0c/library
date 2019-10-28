<?php

header("Access-Control-Allow-Origin: *");

require('vendor/autoload.php');
require('app/routes.php');

use App\App;
use App\Models\Book;

$app = new App();




