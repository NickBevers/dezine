<?php
    require __DIR__ . '/vendor/autoload.php';
    use Dezine\Auth\User;
    use Dezine\Helpers\Validate;

    Validate::start();
    
    $userEmail = $_SESSION['email'];
    User::deleteUserContentByEmail($_SESSION["id"]);
    User::deleteUserByEmail($userEmail);

    Validate::end();
    header("Location: login.php");
