<?php

require_once("common.php");
header("Content-Type: text/xml");
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + (60*60*2)) . ' GMT');
header('Cache-Control: public, must-revalidate, max-age=7200');

$view = $container['view'];
echo $view->getFeed($_SERVER['REQUEST_URI']);