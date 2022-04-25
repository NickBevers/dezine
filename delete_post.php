<?php 
    
    include_once(__DIR__ . "/autoloader.php");
    include_once("./helpers/Cleaner.help.php");
    include_once("./helpers/Security.help.php");

	if(!Security::isLoggedIn()) {
        header('Location: login.php');
    }

    try {
        Post::deletePostById($_GET["pid"]);
    } catch (Exception $e) {
        $_SESSION['flash_error'] = "Something went wrong, try again later.";
    }
    
    header("Location: profile.php");
?>