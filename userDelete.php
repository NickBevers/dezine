<?php 
    include_once(__DIR__ . "/autoloader.php");
    $userEmail = $_SESSION['email'];
    User::deleteUserContentByEmail($_SESSION["id"]);
    User::deleteUserByEmail($userEmail);

    header("Location: login.php");
?>