<?php
    require __DIR__ . '/../vendor/autoload.php';
    use Dezine\Helpers\Security;

    if(Security::isLoggedIn()) {
        header('Location: home.php');
    }