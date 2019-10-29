<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PATCH, DELETE");

require('vendor/autoload.php');
require('app/routes.php');

use App\App;
use App\Models\Book;

$app = new App();




