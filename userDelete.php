<?php 
    include_once(__DIR__ . "/classes/User.php");
    session_start();
    $userEmail = $_SESSION['email'];
    User::deleteUserContentByEmail($_SESSION["id"]);
    User::deleteUserByEmail($userEmail);

    header("Location: login.php");
?>