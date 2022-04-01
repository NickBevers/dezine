<?php 
    include_once(__DIR__ . "/helpers/Security.help.php");
    Security::onlyLoggedInUsers();

    include_once(__DIR__ . "/classes/User.php");

    $userEmail = $_SESSION['email'];
    User::deleteUserByEmail($userEmail);

    header("Location: login.php");
?>