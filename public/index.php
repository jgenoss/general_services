<?php

use Dotenv\Dotenv;
use App\Exceptions\ExceptionHandler;
use App\Controllers\Router;

require_once __DIR__ . '/../vendor/autoload.php';

Dotenv::createImmutable(__DIR__ . '/..')->load();

$exceptionHandler = new ExceptionHandler();
$router = new Router();
try {
    
    include_once __DIR__ . '/../app/Routes/web.php';
    include_once __DIR__ . '/../app/Routes/api.php';

    $router->run();
} catch (\Throwable $exception) {
    $exceptionHandler->handle($exception);
}

