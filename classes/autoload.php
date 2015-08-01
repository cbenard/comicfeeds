<?php

spl_autoload_register(function ($class) {
    $filename = __DIR__ . '/../classes/' . $class . '.php';
    if (file_exists($filename)) {
        include(__DIR__ . '/../classes/' . $class . '.php');
    }
});