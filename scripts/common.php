<?php

ob_start();
libxml_disable_entity_loader(false);

spl_autoload_register(function ($class) {
    include __DIR__ . '/../classes/' . $class . '.php';
});

require(__DIR__ . '/../vendor/autoload.php');

require('scripts/dependency_injection.php');

set_exception_handler(function($ex) {
    ob_end_clean();
    
    header('Content-Type: text/plain');
    http_response_code(500);
    
    echo $ex->getMessage();
});