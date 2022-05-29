<?php
    require __DIR__ . '/vendor/autoload.php';
    use Dezine\Helpers\Validate;
    use Dezine\Helpers\Security;
    use Dezine\Content\Post;
    
    Validate::start();

    if (!Security::isLoggedIn()) {header('Location: login.php');}

    try {
        Post::deletePostById($_GET["pid"]);
    } catch (Exception $e) {
        $_SESSION['flash_error'] = "Something went wrong, try again later.";
    }
    
    header("Location: profile.php");