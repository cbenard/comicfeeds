<?php

spl_autoload_register(function ($class) {
    include __DIR__ . '/../classes/' . $class . '.php';
});