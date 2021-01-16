<?php

namespace Comicfeeds\Controllers;

use Comicfeeds\StorageService;

use Psr\Http\Message\ResponseInterface as Response;

class ViewController
{
    private $store;

    public function __construct(StorageService $store)
    {
        $this->store = $store;
    }

    public function get(Response $response, string $feedName, string $feedType): Response
    {
        $filename = "feed_{$feedName}_{$feedType}";

        $feedText = $this->store->load($filename);

        $response = $response
            ->withHeader('Content-Type', 'text/xml')
            ->withHeader('Expires', gmdate('D, d M Y H:i:s', time() + (60 * 30)) . ' GMT')
            ->withHeader('Cache-Control', 'public, must-revalidate, max-age=1800');

        $response->getBody()->write($feedText);

        return $response;
    }
}
