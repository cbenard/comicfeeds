<?php

use Comicfeeds\{FeedService, DependencyInjection};

require __DIR__ . '/../vendor/autoload.php';

$container = DependencyInjection::CreateContainer();

/** @var FeedService $feedService */
$feedService = $container->get(FeedService::class);

if ($argc > 1) {
	$feedService->Fetch($argv[1]);
} else {
	$feedService->FetchAll();
}
