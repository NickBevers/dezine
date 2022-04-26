<?php 
    session_start();
    spl_autoload_register(function ($class) {
        include_once(__DIR__ . "/classes/" . $class . '.php');
    });