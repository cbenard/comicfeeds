<?php

namespace Comicfeeds\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Stream as Stream;

class HomeController
{
    public function get(Response $response): Response
    {
        $openFile = fopen(__DIR__ . '/../../views/index.html', 'r');
        $stream = new Stream($openFile);
        $response = $response
            ->withHeader('Location', 'https://github.com/cbenard/comicfeeds/')
            ->withBody($stream);
        return $response;
    }
}
