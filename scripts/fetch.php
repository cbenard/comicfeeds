<?php

header("Content-Type: text/plain");
require_once("common.php");

$feed = $container['feed'];
$feedName = $feed->getFeedName($_SERVER['REQUEST_URI']);

$comic = $container[$feedName];
$comic->fetchAndStore();