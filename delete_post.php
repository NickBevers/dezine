<?php 
    include_once("bootstrap.php");
    use \Helpers\Validate;
    use \Helpers\Security;
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