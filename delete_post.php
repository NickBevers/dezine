<?php 
    
    include_once(__DIR__ . "/autoloader.php");
    include_once("./helpers/Cleaner.help.php");
    include_once("./helpers/Security.help.php");

	if(!Security::isLoggedIn()) {
        header('Location: login.php');
    }

    try {
        Post::deletePostById($_GET["p"], $_SESSION["id"]);
    } catch (Exception $e) {
        $_SESSION['flash_error'] = "Something went wrong, try again later.";
    }
    
    header("Location: profile.php?id=" . $_SESSION["id"]);
?>