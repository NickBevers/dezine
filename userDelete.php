<?php
    require __DIR__ . '/vendor/autoload.php';
    use Classes\Auth\User;
    use \Helpers\Validate;

    Validate::start();
    
    $userEmail = $_SESSION['email'];
    User::deleteUserContentByEmail($_SESSION["id"]);
    User::deleteUserByEmail($userEmail);

    Validate::end();
    header("Location: login.php");
