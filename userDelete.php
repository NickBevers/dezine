<?php
    include_once("bootstrap.php");
    include_once("./helpers/Validate.help.php");
    use Classes\Auth\User;
    use \Helpers\Validate;
    
    $userEmail = $_SESSION['email'];
    User::deleteUserContentByEmail($_SESSION["id"]);
    User::deleteUserByEmail($userEmail);

    Validate::end();
    header("Location: login.php");
