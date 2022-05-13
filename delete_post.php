<?php 
    include_once("bootstrap.php");
    include_once("./helpers/Security.help.php");
    include_once("./helpers/Validate.help.php");
    include_once("./helpers/Cleaner.help.php");
    use Classes\Content\Post;
    
    Validate::start();

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