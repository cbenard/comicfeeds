<?php

use Comicfeeds\Controllers\{HomeController, ViewController};
use Comicfeeds\DependencyInjection;

require __DIR__ . '/../vendor/autoload.php';

$container = DependencyInjection::CreateContainer();
$app = \DI\Bridge\Slim\Bridge::create($container);

$app->get('/view/{feedName:\w+}/{feedType:\w+}', [ViewController::class, 'get']);
$app->get('/', [HomeController::class, 'get']);

$app->run();