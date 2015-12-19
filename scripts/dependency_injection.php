<?php

use Pimple\Container;

$container = new Container();
$container['logger'] = function($c) {
    return new Logger;
};
$container['cache'] = function($c) {
    return new CacheService;
};
$container['storage'] = function($c) {
    return new StorageService($c['cache']);
};
$container['feed'] = function($c) {
    return new FeedService($c['logger']);
};
$container['dilbert'] = function($c) {
    return new DilbertService($c['feed'], $c['logger'], $c['storage']);
};
$container['pennyarcade'] = function($c) {
    return new PennyArcadeService($c['feed'], $c['logger'], $c['storage']);
};
$container['wtduck'] = function($c) {
    return new WTDuckService($c['feed'], $c['logger'], $c['storage']);
};
$container['doonesbury'] = function($c) {
    return new DoonesburyService($c['feed'], $c['logger'], $c['storage']);
};
$container['pvp'] = function($c) {
    return new PVPService($c['feed'], $c['logger'], $c['storage']);
};
$container['tabletitans'] = function($c) {
    return new TableTitansService($c['feed'], $c['logger'], $c['storage']);
};
$container['view'] = function ($c) {
    return new ViewService($c['logger'], $c['storage']);
};
