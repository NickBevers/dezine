<?php
    require __DIR__ . '/vendor/autoload.php';
    use \Dezine\Auth\User;
    use \Dezine\Helpers\Validate;

    Validate::start();    
    User::deleteUserContentById($_SESSION["id"]);
    User::deleteUserByEmail($_SESSION['email']);

    Validate::end();
    header("Location: login.php");